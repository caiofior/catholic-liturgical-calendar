<?php
// Should be set to 0 in production
error_reporting(E_ALL);

// Should be set to '0' in production
ini_set('display_errors', '1');
$basePath = '/~caiofior/catholic-liturgical-calendar';
// Settings
$settings = [
    'basePath'=>$basePath,
    'baseUrl'=>'http://'.$_SERVER['SERVER_NAME'].$basePath,
    'theme'=>'technext/small-apps',
    'siteName'=>'Calendario liturgico'
];

// ...
return $settings;