<?php
namespace Tests\ApiBundle\Handler;

use ApiBundle\Handler\RestExceptionHandler;
use \Exception;

class RestExceptionHandlerTest extends \PHPUnit_Framework_Testcase
{
    private $restExceptionHandler;

    protected function setUp()
    {
        $this->restExceptionHandler = new RestExceptionHandler();
    }

    public function testWrap()
    {
        $data['exception'] = new \Exception("test exception");
        $returnArray = $this->restExceptionHandler->wrap($data);
        $this->assertNotEmpty($returnArray);
        $this->assertArrayHasKey('message', $returnArray);
        $this->assertEquals('test exception', $returnArray['message']);
    }
}