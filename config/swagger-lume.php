<?php

return [
    'api' => [
        'title' => 'Documentation'
    ],
    'routes' => [
        'api' => '/api/documentation',
        'docs' => '/docs',
        'oauth2_callback' => '/api/oauth2-callback',
        'assets' => '/swagger-ui-assets',
        'middleware' => [
            'api' => [],
            'asset' => [],
            'docs' => [],
            'oauth2_callback' => []
        ]
    ],
    'paths' => [
        'docs' => base_path('docs/config'),
        'docs_json' => 'swagger.json',
        'annotations' => base_path('docs'),
        'excludes' => [],
        'base' => env('L5_SWAGGER_BASE_PATH', null),
        'views' => base_path('src/frontend/views/vendor/swagger-lume')
    ],
    'security' => [],
    'generate_always' => env('SWAGGER_GENERATE_ALWAYS', false),
    'swagger_version' => env('SWAGGER_VERSION', '3.0'),
    'proxy' => false,
    'additional_config_url' => null,
    'operations_sort' => env('L5_SWAGGER_OPERATIONS_SORT', null),
    'validator_url' => null,
    'constants' => [
        'SWAGGER_LUME_CONST_HOST' => env('SWAGGER_LUME_CONST_HOST', null)
    ]
];