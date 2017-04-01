<?php

namespace AlgoliaApp;

use AlgoliaSearch\Index;
use AlgoliaApp\Entity\EntityInterface;

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
     * @param  mixed $entity An instance of an entity
     * @return int Object ID as set by the search indexer
     */
    public function createInIndex(EntityInterface $entity)
    {
        $newEntityFromIndexer = $this->index->addObject($entity->getAllData());

        return $newEntityFromIndexer['objectID'];
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
