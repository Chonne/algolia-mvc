<?php

$routes = [
    '/^\/$/' => [
        'http_method' => 'GET',
        'controller' => 'AlgoliaApp\Controller',
        'action' => 'runHome',
    ],
    '/^\/api\/1\/apps$/' => [
        'http_method' => 'POST',
        'controller' => 'AlgoliaApp\Controller',
        'action' => 'runAddEntity',
    ],
    '/^\/api\/1\/apps\/(\d+)$/' => [
        'http_method' => 'DELETE',
        'controller' => 'AlgoliaApp\Controller',
        'action' => 'runDeleteEntity',
    ],
];

return $routes;
