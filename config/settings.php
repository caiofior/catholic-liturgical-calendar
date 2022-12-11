<?php

// Should be set to 0 in production
error_reporting(E_ALL);

// Should be set to '0' in production
ini_set('display_errors', '1');
$basePath = '/~caiofior/catholic-liturgical-calendar';
// Settings
$settings = [
    'basePath' => $basePath,
    'baseUrl' => 'http://' . ($_SERVER['SERVER_NAME']??'') . $basePath,
    'theme' => 'technext/small-apps',
    'siteName' => 'Calendario liturgico',
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
        'metadata_dirs' => [__DIR__ . '/../src/Core'],
        // The parameters Doctrine needs to connect to your database.
        // These parameters depend on the driver (for instance the 'pdo_sqlite' driver
        // needs a 'path' parameter and doesn't use most of the ones shown in this example).
        // Refer to the Doctrine documentation to see the full list
        // of valid parameters: https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html
        'connection' => [
            'driver' => 'pdo_mysql',
            'host' => '127.0.0.1',
            'port' => 3306,
            'dbname' => 'catholic_liturgic',
            'user' => 'caiofior',
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
