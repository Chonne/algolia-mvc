<?php

namespace AlgoliaApp;

use AlgoliaApp\Controller\Controller;
use AlgoliaSearch\Client;
use AlgoliaSearch\Index;

class Application
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var string
     */
    private $route;

    /**
     * @var string
     */
    private $action;

    /**
     * @var array
     */
    private $actionParams;

    /**
     * @var Controller
     */
    private $controller;

    /**
     * @var array
     */
    private $routes;

    /**
     *
     * @param array $config
     * @param array $routes
     */
    public function __construct(array $config, array $routes)
    {
        $this->config = $config;
        $this->routes = $routes;
    }

    /**
     *
     */
    public function run()
    {
        try {
            $this->initRoute();

            if (is_callable([$this->controller, $this->action])) {
                if (empty($this->actionParams)) {
                    call_user_func([$this->controller, $this->action]);
                } else {
                    call_user_func_array([$this->controller, $this->action], $this->actionParams);
                }
            } else {
                throw new \InvalidArgumentException('Unknown action: ' . $this->action, 404);
            }
        } catch (\Exception $e) {
            (new Response())->renderError($e->getCode(), $e->getMessage());
        }
    }

    /**
     *
     * @throws \Exception
     */
    private function initRoute()
    {
        $scriptFilename = $_SERVER['SCRIPT_NAME'];
        $route = $_SERVER['REQUEST_URI'];

        // strip out the filename in case it was set
        if (strpos($route, $scriptFilename) === 0) {
            $route = substr($route, strlen($scriptFilename));

            if (strpos($route, '/') !== 0) {
                $route = '/';
            }
        }

        list($controller, $action, $actionParams) = $this->getRouteAction($route, $_SERVER['REQUEST_METHOD']);

        if (empty($action)) {
            throw new \InvalidArgumentException('Unknown route: "' . $route . '" for method "' . $_SERVER['REQUEST_METHOD'] . '"', 404);
        }

        $this->action = $action;
        $this->actionParams = $actionParams;
        $this->route = $route;
        $this->startController($controller);
    }

    private function startController($controller)
    {
        // todo: check the existence of $controller and validity of $controller->$action()
        $this->controller = new $controller(
            $this->config,
            new Response($this->config),
            new Model($this->initSearchIndex($this->config['parameters']['algolia']))
        );
    }

    /**
     *
     * @param  string  $route
     * @param  string  $method HTTP request method
     *
     * @return array
     */
    private function getRouteAction($route, $method)
    {
        $controller = '';
        $action = '';
        $actionParams = [];

        foreach ($this->routes as $authRoutePattern => $authRoute) {
            if ($method === $authRoute['http_method'] && preg_match($authRoutePattern, $route, $matches) === 1) {
                $controller = $authRoute['controller'];
                $action = $authRoute['action'];

                if (count($matches) > 1) {
                    array_shift($matches);
                    $actionParams = $matches;
                }

                break;
            }
        }

        return [$controller, $action, $actionParams];
    }

    /**
     * Initializes Algolia's index
     *
     * @param array $indexParams
     *
     * @return Index
     * @throws \Exception
     */
    private function initSearchIndex(array $indexParams)
    {
        // todo: inject this in the constructor
        $client = new Client(
            $indexParams['applicationID'],
            $indexParams['apiKey_admin']
        );

        return $client->initIndex($indexParams['indexName']);
    }
}
