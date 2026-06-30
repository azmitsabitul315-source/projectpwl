<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Api extends BaseConfig
{
    /**
     * List of valid API keys. In production, replace with secure storage.
     * Key format: arbitrary string. Multiple keys allowed.
     */
    public array $keys = [
        // Example key — change this before deploying
        'demo' => 'CHANGE_ME_API_KEY_ABC1234567890',
        // Key from .env
        'env_key' => 'my-secret-token' 
    ];

    /**
     * Require HTTPS for API requests
     */
    public bool $requireHttps = false;
    
    public function __construct()
    {
        parent::__construct();
        // Automatically load MY_API_KEY from .env if available
        if (env('MY_API_KEY')) {
            $this->keys['env_key'] = env('MY_API_KEY');
        }
    }
}
