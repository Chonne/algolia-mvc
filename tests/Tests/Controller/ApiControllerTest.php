<?php

namespace AlgoliaSearch\Tests\Controller;

use AlgoliaApp\Controller\ApiController;
use AlgoliaApp\Model;
use AlgoliaApp\Response;

/**
 * Class ApiControllerTest
 *
 * @package AlgoliaSearch\Tests\Controller
 * @covers ApiController
 */
class ApiControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ApiController::runAddEntity
     */
    public function testRunAddEntity()
    {
        $_POST = [
            'data' => '{"foo":"bar"}',
        ];

        $mockModel = $this->createMock(Model::class);

        $mockModel->method('validateData')
            ->will($this->returnArgument(0))
            ->with($this->anything())
        ;

        $mockModel->method('createInIndex')
            ->willReturn(101)
            ->with($this->anything())
        ;

        $mockResponse = $this->createMock(Response::class);

        $mockResponse->method('setResponseCode')
            ->willReturn(null)
        ;

        $mockResponse->method('render')
            ->willReturn(null)
            ->with($this->anything())
        ;

        $apiController = new ApiController([], $mockResponse, $mockModel);

        $apiController->runAddEntity();
    }

    /**
     * @covers ApiController::runDeleteEntity
     */
    public function testRunDeleteEntity()
    {
        $mockModel = $this->createMock(Model::class);

        $mockModel->method('delete')
            ->willReturn(null)
            ->with($this->greaterThan(0))
        ;

        $mockResponse = $this->createMock(Response::class);

        $apiController = new ApiController([], $mockResponse, $mockModel);

        $apiController->runDeleteEntity(101);
    }
}
