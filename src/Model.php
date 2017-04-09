<?php

namespace AlgoliaApp;

use AlgoliaSearch\Index;

/**
 * Takes care of data validation, creation and deletion. Only one kind of object
 * is supported so no need for a separate class
 */
class Model
{
    private $fields = [
        'name',
        'image',
        'link',
        'category',
        'rank',
    ];

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
     * @param array $data
     * @return int Object ID as set by the search indexer
     */
    public function createInIndex(array $data)
    {
        $newEntityFromIndexer = $this->index->addObject($data);

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

    /**
     * Validates data before sending it to the indexer
     * @param  array  $data
     * @return array
     */
    public function validateData(array $data)
    {
        $this->hasUnknownFields($data);

        $this->hasAllRequiredFields($data);

        return $data;
    }

    /**
     * Checks if the entity has all the required fields
     * @param  array   $data
     * @return boolean
     */
    private function hasAllRequiredFields(array $data)
    {
        foreach ($this->fields as $field) {
            if (!array_key_exists($field, $data)) {
                throw new \InvalidArgumentException('Key is required but was not found: ' . $field);
            } elseif (empty($data[$field])) {
                throw new \InvalidArgumentException('Field cannot be empty: ' . $field);
            }
        }
    }

    /**
     * Checks if the data doesn't have any extra unwanted fields and throws an
     * exception if it's the case
     * @param  array   $data
     * @return boolean
     */
    private function hasUnknownFields(array $data)
    {
        $keys = array_keys($data);

        foreach ($keys as $key) {
            if (!in_array($key, $this->fields)) {
                throw new \OutOfRangeException('Unknown field: '.$key);
            }
        }

        return $data;
    }
}
