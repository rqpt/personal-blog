<?php

return [

    'cache' => env('TORCHLIGHT_CACHE_DRIVER'),

    'cache_seconds' => env('TORCHLIGHT_CACHE_TTL', 60 * 60 * 24 * 30),

    'theme' => [
        'dark' => env('TORCHLIGHT_DARK_THEME', 'github-dark'),
        'light' => env('TORCHLIGHT_LIGHT_THEME', 'github-light'),
    ],

    'token' => env('TORCHLIGHT_TOKEN'),

    'blade_components' => true,

    'host' => env('TORCHLIGHT_HOST', 'https://api.torchlight.dev'),

    'tab_width' => 4,

    'snippet_directories' => [
        resource_path(),
    ],

    'options' => [
        'lineNumbers' => false,
    ],

];
