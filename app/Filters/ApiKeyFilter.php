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

        // Optionally require HTTPS
        if ($config->requireHttps && ! $request->isSecure()) {
            return service('response')->setStatusCode(403)->setJSON(['status' => false, 'message' => 'HTTPS required']);
        }

        // Accept either Authorization: Bearer <key> or X-API-KEY header
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

        // Check against configured keys
        if (! in_array($key, $config->keys, true)) {
            return service('response')->setStatusCode(403)->setJSON(['status' => false, 'message' => 'Invalid API key']);
        }

        // Passed
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // nothing to do
    }
}
