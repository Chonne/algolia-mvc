<?php

require_once __DIR__ . '/../vendor/autoload.php';

$config = require_once __DIR__ . '/../config/config.php';

$app = new AlgoliaApp\Application($config);

$app->run();
