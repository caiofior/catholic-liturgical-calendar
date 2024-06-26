<?php

// Should be set to 0 in production
error_reporting(E_ALL);
// Should be set to '0' in production
ini_set('display_errors', '1');
$basePath = '/~caio/preces.it';
// Settings
$settings = [
    'basePath' => $basePath,
    'baseDir' => realpath(__DIR__.'/../'),
    'baseUrl' => ($_SERVER['HTTPS']??'' == 'on' ? 'https':'http').'://' . ($_SERVER['SERVER_NAME'] ?? '') . $basePath,
    'siteUrl' => 'https://www.preces.it',
    'theme' => 'technext/small-apps',
    'siteName' => 'Calendario liturgico',
    'locale' => 'it_IT',
    'encryption' => [
        'method' => 'AES-256-CBC',
        'encryption_key' => 'EYsi/LVJh0+Sk+gVa1WbCKfOry/FD4NmjQzVF/3Chxc=',
        'password' => 'snakeoil',
        'salt' => 'spickyText',
        'iv' => 'KxaXhSa5e6vos+60FC/4Cg=='
    ],
    'mail' => [
        'host'=>'mail.test.it',
        'auth'=>true,
        'username'=>'test.it',
        'password'=>'snakeoin',
        'port'=>587,
        'secure'=>'tls'
    ],
    'doctrine' => [
        // Enables or disables Doctrine metadata caching
        // for either performance or convenience during development.
        'dev_mode' => true,
        // Path where Doctrine will cache the processed metadata
        // when 'dev_mode' is false.
        'cache_dir' => sys_get_temp_dir(),
        // List of paths where Doctrine will search for metadata.
        // Metadata can be either YML/XML files or PHP classes annotated
        // with comments or PHP8 attributes.
        'metadata_dirs' => [__DIR__ . '/../src/Core', __DIR__ . '/../src/CatholicLiturgical'],
        // The parameters Doctrine needs to connect to your database.
        // These parameters depend on the driver (for instance the 'pdo_sqlite' driver
        // needs a 'path' parameter and doesn't use most of the ones shown in this example).
        // Refer to the Doctrine documentation to see the full list
        // of valid parameters: https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html
        'connection' => [
            'driver' => 'pdo_mysql',
            'host' => '127.0.0.1',
            'port' => 3306,
            'dbname' => 'precesit69987',
            'user' => 'caio',
            'password' => 'topolino',
            'charset' => 'UTF8',
            'options' => [
                '1002' => 'SET NAMES "UTF8" COLLATE "utf8_unicode_ci"'
            ]
        ]
    ]
];
// ...
return $settings;
