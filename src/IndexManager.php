<?php

namespace AlgoliaApp;


class IndexManager
{
    /**
     * @var AlgoliaSearch\Client
     */
    private $client;

    public function __construct($appId, $apiKey)
    {
        $this->client = new Client($appId, $apiKey);
    }

    public function addEntity(array $data)
    {
        $this->client->addObject($data);
    }

    public function deleteEntity($id)
    {
        $this->client->deleteObject($id);
    }
}
