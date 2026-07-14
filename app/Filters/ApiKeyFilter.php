<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Api as ApiConfig;

class ApiKeyFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $config = new ApiConfig();

        // secara opsional mewajibkan HTTPS
        if ($config->requireHttps && ! $request->isSecure()) {
            return service('response')->setStatusCode(403)->setJSON(['status' => false, 'message' => 'HTTPS required']);
        }

        // memeriksa header Autorization: Barer <key> atau X-API-KEY
        $authHeader = $request->getHeaderLine('Authorization');
        $apiKeyHeader = $request->getHeaderLine('X-API-KEY');

        $key = null;
        if ($authHeader && stripos($authHeader, 'Bearer ') === 0) {
            $key = trim(substr($authHeader, 7));
        } elseif ($apiKeyHeader) {
            $key = trim($apiKeyHeader);
        }

        if (! $key) {
            return service('response')->setStatusCode(401)->setJSON(['status' => false, 'message' => 'API key required']);
        }

        // Periksa terhadap kunci yang dikonfigurasi
        if (! in_array($key, $config->keys, true)) {
            return service('response')->setStatusCode(403)->setJSON(['status' => false, 'message' => 'Invalid API key']);
        }

        
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        
    }
}
