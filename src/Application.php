<?php

namespace AlgoliaApp;

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
                throw new \Exception('Unknown action: ' . $this->action, 404);
            }
        } catch (\Exception $e) {
            $this->renderError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Display the error message, with the appropriate HTTP response code
     * @param  int $code
     * @param  string $message
     */
    private function renderError($code = 500, $message = 'An error has occurred')
    {
        $expectedCodes = [
            400,
            404,
            500,
        ];

        if (!in_array($code, $expectedCodes)) {
            $code = 500;
        }

        http_response_code($code);

        echo $message;
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
            throw new \Exception('Unknown route: "' . $route . '" for method "' . $_SERVER['REQUEST_METHOD'] . '"', 404);
        }

        $this->action = $action;
        $this->actionParams = $actionParams;
        $this->route = $route;
        // todo: check the existence of $controller and validity of $controller->$action()
        $this->controller = new $controller($this->config, new Model($this->initSearchIndex($this->config['parameters']['algolia'])));
    }

    /**
     *
     * @param  string  $route
     * @param  string  $method HTTP request method
     * @return boolean
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
     * @param array $indexParams
     * @return Index
     */
    private function initSearchIndex(array $indexParams)
    {
        // todo: inject this in the constructor
        $client = new Client(
            $indexParams['applicationID'],
            $indexParams['apiKey_admin']
        );

        $index = $client->initIndex($indexParams['indexName']);

        return $index;
    }
}
