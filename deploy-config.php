<?php

return [
    'production' => [
        'host' => getenv('PROD_HOST'),
        'username' => getenv('PROD_USERNAME'),
        'path' => '/var/www/html',
        'branch' => 'main',
        'composer_options' => '--no-dev --optimize-autoloader',
    ],
    
    'staging' => [
        'host' => getenv('STAGING_HOST'),
        'username' => getenv('STAGING_USERNAME'),
        'path' => '/var/www/staging',
        'branch' => 'develop',
        'composer_options' => '--no-dev',
    ],
    
    'database' => [
        'production' => [
            'host' => getenv('PROD_DB_HOST'),
            'name' => getenv('PROD_DB_NAME'),
            'user' => getenv('PROD_DB_USER'),
            'pass' => getenv('PROD_DB_PASS'),
        ],
        'staging' => [
            'host' => getenv('STAGING_DB_HOST'),
            'name' => getenv('STAGING_DB_NAME'),
            'user' => getenv('STAGING_DB_USER'),
            'pass' => getenv('STAGING_DB_PASS'),
        ],
    ],
]; 