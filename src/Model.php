<?php

namespace AlgoliaApp;

use AlgoliaSearch\Index;

class Model
{
    /**
     * @var array
     */
    private $index;

    public function __construct(Index $index)
    {
        $this->index = $index;
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

        $newEntityFromIndexer = $this->index->addObject($data);

        $entity->setObjectId($newEntityFromIndexer['objectID']);

        return $entity;
    }

    /**
     * Removes the entity from the index
     * @param  string|integer $id ObjectID of the entity to remove
     */
    public function delete($id)
    {
        $this->index->deleteObject($id);
    }
}
