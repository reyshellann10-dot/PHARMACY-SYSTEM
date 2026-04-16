<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseConfig
{
    public $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        // Comment out custom filters until you create them
        // 'auth'          => \App\Filters\AuthFilter::class,
        // 'admin'         => \App\Filters\AdminFilter::class,
        // 'pharmacist'    => \App\Filters\PharmacistFilter::class,
        // 'cashier'       => \App\Filters\CashierFilter::class,
    ];

    public $globals = [
        'before' => [
            // 'csrf' => ['except' => [
            //     'api/*',
            //     'sales/add-to-cart',
            //     'sales/remove-from-cart',
            //     'sales/update-cart',
            //     'sales/checkout'
            // ]],
            'invalidchars',
        ],
        'after' => [
            'toolbar',
            'secureheaders',
        ],
    ];

    public $methods = [];

    public $filters = [];
}