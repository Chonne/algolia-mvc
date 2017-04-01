<?php

namespace AlgoliaApp;

class Response
{
    /**
     * @var array
     */
    private $config;

    public function __construct(array $config = null)
    {
        $this->config = $config;

        http_response_code(200);
    }

    /**
     * Display the error message, with the appropriate HTTP response code
     * @param  int $code
     * @param  string $message
     */
    public function renderError($code = 500, $message = 'An error has occurred')
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

        $this->render($message);
    }

    public function renderTemplate($templateName, $templateParams = null)
    {
        $templatePath = $this->config['paths']['templates'] . '/' . $templateName;

        ob_start();

        require $templatePath;

        $content = ob_get_contents();

        ob_end_clean();

        $this->render($content);
    }

    public function render($content)
    {
        echo $content;
    }

    public function setResponseCode($code)
    {
        http_response_code($code);
    }
}
