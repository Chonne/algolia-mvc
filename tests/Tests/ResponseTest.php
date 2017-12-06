<?php

namespace AlgoliaSearch\Tests;

use AlgoliaApp\Response;

/**
 * Class ResponseTest
 *
 * @package AlgoliaSearch\Tests
 * @covers Response
 */
class ResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Response::render
     */
    public function testRender()
    {
        $expectedOutput = 'foo';
        $this->expectOutputString($expectedOutput);

        (new Response())->render($expectedOutput);
        $this->assertEquals(200, http_response_code());
    }

    /**
     * @covers Response::renderError
     */
    public function testRenderError()
    {
        $this->expectOutputString('An error has occurred');

        (new Response())->renderError();
        $this->assertEquals(500, http_response_code());
    }

    /**
     * @covers Response::renderError
     * @dataProvider customCodesAndMessagesProvider
     */
    public function testRenderErrorWithCustomCodeAndMessage($httpCode, $msg)
    {
        $this->expectOutputString($msg);

        (new Response())->renderError($httpCode, $msg);
        $this->assertEquals($httpCode, http_response_code());
    }

    /**
     * @covers Response::renderError
     */
    public function testRenderErrorWithUnsupportedCode()
    {
        $this->expectOutputString('An error has occurred');

        (new Response())->renderError(666);
        $this->assertEquals(500, http_response_code());
    }

    public function customCodesAndMessagesProvider()
    {
        return [
            [400, 'A custom 400 error has occurred'],
            [404, 'A custom 404 error has occurred'],
            [500, 'A custom 500 error has occurred'],
        ];
    }
}
