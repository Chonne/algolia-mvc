<?php

namespace AlgoliaSearch\Tests\Controller;

use AlgoliaApp\Controller\FrontController;
use AlgoliaApp\Model;
use AlgoliaApp\Response;

/**
 * Class FrontControllerTest
 *
 * @package AlgoliaSearch\Tests\Controller
 * @covers FrontController
 */
class FrontControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers FrontController::runHome
     */
    public function testRunHome()
    {
        $mockConfig = [
            'parameters' => [
                'algolia' => [
                    'applicationId' => '',
                    'apiKey_search' => '',
                    'indexName' => '',
                ],
            ],
        ];

        $mockModel = $this->createMock(Model::class);

        $mockResponse = $this->createMock(Response::class);

        $mockResponse->method('renderTemplate')
            ->willReturn(null)
            ->with($this->anything(), $this->anything())
        ;

        $frontController = new FrontController($mockConfig, $mockResponse, $mockModel);

        $frontController->runHome();
    }
}
