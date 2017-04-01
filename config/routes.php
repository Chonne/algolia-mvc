<?php

$routes = [
    '/^\/$/' => [
        'http_method' => 'GET',
        'controller' => 'AlgoliaApp\Controller\FrontController',
        'action' => 'runHome',
    ],
    '/^\/api\/1\/apps$/' => [
        'http_method' => 'POST',
        'controller' => 'AlgoliaApp\Controller\ApiController',
        'action' => 'runAddEntity',
    ],
    '/^\/api\/1\/apps\/(\d+)$/' => [
        'http_method' => 'DELETE',
        'controller' => 'AlgoliaApp\Controller\ApiController',
        'action' => 'runDeleteEntity',
    ],
];

return $routes;
