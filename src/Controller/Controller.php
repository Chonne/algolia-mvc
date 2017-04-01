<?php

namespace AlgoliaApp\Controller;

use AlgoliaApp\Model;
use AlgoliaApp\Response;

abstract class Controller
{
    protected $config;
    protected $model;
    protected $response;

    public function __construct(array $config, Response $response, Model $model)
    {
        $this->config = $config;
        $this->response = $response;
        $this->model = $model;
    }
}
