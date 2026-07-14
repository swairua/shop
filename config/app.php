<?php
return [
    'name' => 'Helix General Hardware',
    'version' => '1.0.0',
    'debug' => false,
    'url' => 'https://www.helixgeneralhardware.com',
    'timezone' => 'Africa/Nairobi',
    'currency' => 'KES',
    'currency_symbol' => 'KSh',
    'currency_position' => 'before',
    'locale' => 'en',
    'paginate' => [
        'per_page' => 20,
        'per_page_admin' => 50
    ],
    'image' => [
        'max_size' => 5242880,
        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        'quality' => 80,
        'thumbnail' => ['width' => 150, 'height' => 150],
        'medium' => ['width' => 400, 'height' => 400],
        'large' => ['width' => 800, 'height' => 800]
    ],
    'session' => [
        'lifetime' => 86400,
        'secure' => true,
        'http_only' => true
    ],
    'mail' => [
        'driver' => 'smtp',
        'host' => '',
        'port' => 587,
        'username' => '',
        'password' => '',
        'encryption' => 'tls',
        'from_address' => 'info@helixgeneralhardware.com',
        'from_name' => 'Helix General Hardware'
    ]
];
