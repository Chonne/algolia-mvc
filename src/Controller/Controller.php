<?php

namespace AlgoliaApp\Controller;

use AlgoliaApp\Model;

abstract class Controller
{
    protected $config;

    public function __construct(array $config, Model $model)
    {
        $this->config = $config;
        $this->model = $model;
    }
}
