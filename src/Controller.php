<?php

namespace AlgoliaApp;

use AlgoliaSearch\Client;
use AlgoliaApp\Entity\App as AppEntity;

class Controller
{
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->model = new Model($config);
    }

    public function runHome()
    {
        $templatePath = $this->config['paths']['templates'] . '/' . 'search.php';

        $templateParams = [
            'algolia_applicationID' => $this->config['parameters']['algolia']['applicationID'],
            'algolia_apiKey' => $this->config['parameters']['algolia']['apiKey_search'],
            'algolia_indexName' => $this->config['parameters']['algolia']['indexName'],
        ];

        require $templatePath;
    }

    public function runAddEntity()
    {
        if (empty($_POST['data'])) {
            throw new \Exception('Data is missing', 400);
        }

        // TODO: check if json_decode didn't throw an error
        $data = json_decode($_POST['data'], true);

        try {
            $newApp = $this->model->create('App', $data);

            http_response_code(201);

            echo $newApp->getObjectId();
        } catch (\Exception $e) {
            throw new \Exception('Entity could not be created: ' . $e->getMessage(), 400, $e);
        }
    }

    /**
     * Deletes an entity from the index
     * @param  string|int $id
     */
    public function runDeleteEntity($id)
    {
        $this->model->delete($id);

        // Not necessary as it's the code by default
        http_response_code(200);
    }
}
