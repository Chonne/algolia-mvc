<?php

namespace AlgoliaApp;

use AlgoliaSearch\Client;

class Model
{
    /**
     * @var array
     */
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Creates an index with the data provided
     * @param  string $type Name of the entity's class (without "Entity")
     * @param  array  $data Data passed to the entity's constructor
     * @return mixed       Instanciated entity
     */
    public function create($type, array $data)
    {
        $className = 'AlgoliaApp\\Entity\\' . $type;

        $entity = new $className($data);

        $index = $this->initIndex();

        $newEntityFromIndexer = $index->addObject($data);

        $entity->setObjectId($newEntityFromIndexer['objectID']);

        return $entity;
    }

    /**
     * Removes the entity from the index
     * @param  string|integer $id ObjectID of the entity to remove
     */
    public function delete($id)
    {
        $index = $this->initIndex();

        $index->deleteObject($id);
    }

    /**
     * Initializes Algolia's index
     * @todo execute this in the constructor?
     * @return AlgoliaSearch\Index
     */
    private function initIndex()
    {
        $client = new Client(
            $this->config['parameters']['algolia']['applicationID'],
            $this->config['parameters']['algolia']['apiKey_admin']
        );

        $index = $client->initIndex($this->config['parameters']['algolia']['indexName']);

        return $index;
    }
}
