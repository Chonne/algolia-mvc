<?php

require_once __DIR__ . '/../vendor/autoload.php';

$config = require_once __DIR__ . '/../config/config.php';

// todo: display errors or let the php configuration decide if debug mode is active
if (!$config['parameters']['debug']) {
    ini_set('display_errors', 0);
}

$app = new AlgoliaApp\Application($config);

$app->run();
