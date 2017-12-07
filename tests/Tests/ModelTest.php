<?php

namespace AlgoliaSearch\Tests;

use AlgoliaApp\Model;
use AlgoliaSearch\Index;

/**
 * Class ModelTest
 *
 * @package AlgoliaSearch\Tests
 * @covers Model
 */
class ModelTest extends \PHPUnit_Framework_TestCase
{
    private $dataFields = [
        'name' => 'mock',
        'image' => 'mock',
        'link' => 'mock',
        'category' => 'mock',
        'rank' => 'mock',
    ];

    /**
     * @covers Model::createInIndex
     */
    public function testCreateInIndex()
    {
        $objectId = 101;

        $index = $this->createMock(Index::class);

        $index->method('addObject')
            ->willReturn(['objectID' => $objectId])
            ->with($this->anything())
        ;

        $this->assertEquals($objectId, (new Model($index))->createInIndex([]));
    }

    /**
     * @covers Model::delete
     */
    public function testDelete()
    {
        $objectId = 101;

        $index = $this->createMock(Index::class);

        $index->method('deleteObject')
            ->willReturn(null)
            ->with($this->anything())
        ;

        (new Model($index))->delete($objectId);
    }

    /**
     * @covers Model::validateData
     * @covers Model::hasAllRequiredFields
     * @covers Model::hasUnknownFields
     */
    public function testValidateData()
    {
        $data = $this->dataFields;

        $index = $this->createMock(Index::class);

        $this->assertEquals($data, (new Model($index))->validateData($data));
    }

    /**
     * @covers Model::validateData
     * @covers Model::hasAllRequiredFields
     * @covers Model::hasUnknownFields
     */
    public function testValidateDataWithMissingField()
    {
        $data = $this->dataFields;
        array_pop($data);

        $index = $this->createMock(Index::class);

        $this->expectException(\InvalidArgumentException::class);

        (new Model($index))->validateData($data);
    }

    /**
     * @covers Model::validateData
     * @covers Model::hasAllRequiredFields
     * @covers Model::hasUnknownFields
     */
    public function testValidateDataWithEmptyField()
    {
        $data = $this->dataFields;
        $data[key($data)] = '';

        $index = $this->createMock(Index::class);

        $this->expectException(\InvalidArgumentException::class);

        (new Model($index))->validateData($data);
    }

    /**
     * @covers Model::validateData
     * @covers Model::hasAllRequiredFields
     * @covers Model::hasUnknownFields
     */
    public function testValidateDataWithUnknownField()
    {
        $data = $this->dataFields;
        $data['newField'] = 'mock';

        $index = $this->createMock(Index::class);

        $this->expectException(\OutOfRangeException::class);

        (new Model($index))->validateData($data);
    }
}
