<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\Cors;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\ForceHTTPS;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseFilters
{
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'cors'          => Cors::class,
        'forcehttps'    => ForceHTTPS::class,
        'pagecache'     => PageCache::class,
        'performance'   => PerformanceMetrics::class,
        'auth'          => \App\Filters\Auth::class, // Pastikan file Auth.php ada di App/Filters
    ];

    public array $required = [
        'before' => [
            'forcehttps',
            'pagecache',
        ],
        'after' => [
            'pagecache',
            'performance',
            'toolbar',
        ],
    ];

    public array $globals = [
        'before' => [
            // 'csrf', 
            'auth' => [
                'except' => [
                    'login', 'login/*', 
                    'login-auth', 'login-auth/*',
                    'index.php/login', 'index.php/login-auth',
                    '/', 'front/*'
                ]
            ],
        ],
        'after' => [
            'toolbar',
        ],
    ];

    public array $methods = [];

    // Jika global filter di atas tetap tidak mempan, 
    // kita daftarkan secara spesifik di sini:
    public array $filters = [
        'auth' => ['before' => ['produk', 'produk/*']],
    ];
}