<?php

namespace AlgoliaApp;

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

    private $routes = [
        '/^\/$/' => [
            'http_method' => 'GET',
            'action' => 'runHome',
        ],
        '/^\/api\/1\/apps$/' => [
            'http_method' => 'POST',
            'action' => 'runAddEntity',
        ],
        '/^\/api\/1\/apps\/(\d+)$/' => [
            'http_method' => 'DELETE',
            'action' => 'runDeleteEntity',
        ],
    ];

    /**
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->controller = new Controller($config);
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

        list($action, $actionParams) = $this->getRouteAction($route, $_SERVER['REQUEST_METHOD']);

        if (empty($action)) {
            throw new \Exception('Unknown route: "' . $route . '" for method "' . $_SERVER['REQUEST_METHOD'] . '"', 404);
        }

        $this->action = $action;
        $this->actionParams = $actionParams;
        $this->route = $route;
    }

    /**
     *
     * @param  string  $route
     * @param  string  $method HTTP request method
     * @return boolean
     */
    private function getRouteAction($route, $method)
    {
        $action = '';
        $actionParams = [];

        foreach ($this->routes as $authRoutePattern => $authRoute) {
            if ($method === $authRoute['http_method'] && preg_match($authRoutePattern, $route, $matches) === 1) {
                $action = $authRoute['action'];
                if (count($matches) > 1) {
                    array_shift($matches);
                    $actionParams = $matches;
                }
                break;
            }
        }

        return [$action, $actionParams];
    }
}
