<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\Cors;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;

class Filters extends BaseFilters
{
    public array $aliases = [
        'csrf'    => CSRF::class,
        'toolbar' => DebugToolbar::class,
        'auth'    => \App\Filters\Auth::class, 
        'apikey'  => \App\Filters\ApiKeyFilter::class,
    ];

    public array $required = [];

    public array $globals = [
        'before' => [
            'csrf' => ['except' => ['api/*']], 
            'auth' => [
                'except' => [
                    'login', 'login/*', 
                    'login-auth', 'login-auth/*',
                    'index.php/login', 'index.php/login-auth',
                    '/', 'front/*',
                    'kuliner/detail/*', 'kuliner/*/reviews',
                    'kuliner/cariKoordinat',
                    'api/*',
                    'index.php/kuliner/cariKoordinat',
                    'index.php/api/*',
                ]
            ],
        ],
        'after' => [
            'toolbar',
        ],
    ];

    public array $methods = [];

    public array $filters = [];
}