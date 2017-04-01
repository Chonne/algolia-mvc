<?php

$localParameters = require_once __DIR__ . '/parameters.php';

$config = [
    'paths' => [
        'templates' => realpath(__DIR__ . '/../template'),
    ],
    'parameters' => [
        'algolia' => [
            'applicationID' => '',
            'apiKey_search' => '',
            'apiKey_admin' => '',
            'indexName' => '',
        ],
        'debug' => false,
    ],
];

$config['parameters'] = array_merge($config['parameters'], $localParameters);

return $config;
