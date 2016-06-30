<?php
namespace Tests\ApiBundle\Entity;

use ApiBundle\Entity\CustomerInfoRequest as CustomerInfoRequest;
use Translatable\Fixture\Type\Custom;

class CustomerInfoRequestTest extends \PHPUnit_Framework_Testcase
{
    private $customerInfoRequest;

    protected function setUp()
    {
        $this->customerInfoRequest = new CustomerInfoRequest();
    }

    public function testStatusConstants()
    {
        $this->assertEquals('TBP', CustomerInfoRequest::STATUS_TBP);
        $this->assertEquals('RTC', CustomerInfoRequest::STATUS_RTC);
        $this->assertEquals('RQC', CustomerInfoRequest::STATUS_RQC);
    }

    public function testGetId()
    {
        //since this is decoupled from db we will get null for newly created object
        $this->assertNull($this->customerInfoRequest->getId());
    }

    public function testSetEmail()
    {
        $returnObject = $this->customerInfoRequest->setEmail('test@test.com');
        $this->assertInstanceOf('ApiBundle\Entity\CustomerInfoRequest', $returnObject);
        $this->assertEquals('test@test.com', $returnObject->getEmail());
    }

    public function testGetEmail()
    {
        $this->customerInfoRequest->setEmail('test@test.com');
        $this->assertEquals('test@test.com', $this->customerInfoRequest->getEmail());
    }

    public function testSetFirstName()
    {
        $returnObject = $this->customerInfoRequest->setFirstName('Test');
        $this->assertInstanceOf('ApiBundle\Entity\CustomerInfoRequest', $returnObject);
        $this->assertEquals('Test', $returnObject->getFirstName());
    }

    public function testGetFirstName()
    {
        $this->customerInfoRequest->setFirstName('Test');
        $this->assertEquals('Test', $this->customerInfoRequest->getFirstName());
    }

    public function testSetLastName()
    {
        $returnObject = $this->customerInfoRequest->setLastName('Test');
        $this->assertInstanceOf('ApiBundle\Entity\CustomerInfoRequest', $returnObject);
        $this->assertEquals('Test', $returnObject->getLastName());
    }

    public function testGetLastName()
    {
        $this->customerInfoRequest->setLastName('Test');
        $this->assertEquals('Test', $this->customerInfoRequest->getLastName());
    }

    public function testSetPhoneNumber()
    {
        $returnObject = $this->customerInfoRequest->setPhoneNumber('+111222333444');
        $this->assertInstanceOf('ApiBundle\Entity\CustomerInfoRequest', $returnObject);
        $this->assertEquals('+111222333444', $returnObject->getPhoneNumber());
    }

    public function testGetPhoneNumber()
    {
        $this->customerInfoRequest->setPhoneNumber('+111222333444');
        $this->assertEquals('+111222333444', $this->customerInfoRequest->getPhoneNumber());
    }

    public function testSetMessage()
    {
        $returnObject = $this->customerInfoRequest->setMessage('This is test message');
        $this->assertInstanceOf('ApiBundle\Entity\CustomerInfoRequest', $returnObject);
        $this->assertEquals('This is test message', $returnObject->getMessage());
    }

    public function testGetMessage()
    {
        $this->customerInfoRequest->setMessage('This is test message');
        $this->assertEquals('This is test message', $this->customerInfoRequest->getMessage());
    }

    public function testSetStatus()
    {
        $statusArray = [
            CustomerInfoRequest::STATUS_TBP,
            CustomerInfoRequest::STATUS_RTC,
            CustomerInfoRequest::STATUS_RQC
        ];
        foreach($statusArray as $status){
            $returnObject = $this->customerInfoRequest->setStatus($status);
            $this->assertInstanceOf('ApiBundle\Entity\CustomerInfoRequest', $returnObject);
            $this->assertEquals($this->customerInfoRequest->getStatus(), $status);
        }

        //test setting status that isn't predefined
        $this->customerInfoRequest->setStatus(CustomerInfoRequest::STATUS_TBP);
        $this->customerInfoRequest->setStatus('TestStatus');
        $this->assertEquals(CustomerInfoRequest::STATUS_TBP, $this->customerInfoRequest->getStatus());
    }

    public function testSetCreated()
    {
        $date = new \DateTime('now');
        $returnObject = $this->customerInfoRequest->setCreated($date);
        $this->assertInstanceOf('ApiBundle\Entity\CustomerInfoRequest', $returnObject);
        $this->assertEquals($this->customerInfoRequest->getCreated(), $date);
    }

    public function testGetCreated()
    {
        $date = new \DateTime('now');
        $this->customerInfoRequest->setCreated($date);
        $this->assertEquals($this->customerInfoRequest->getCreated(), $date);
    }

    public function testSetUpdated()
    {
        $date = new \DateTime('now');
        $returnObject = $this->customerInfoRequest->setUpdated($date);
        $this->assertInstanceOf('ApiBundle\Entity\CustomerInfoRequest', $returnObject);
        $this->assertEquals($this->customerInfoRequest->getUpdated(), $date);
    }

    public function testGetUpdated()
    {
        $date = new \DateTime('now');
        $this->customerInfoRequest->setUpdated($date);
        $this->assertEquals($this->customerInfoRequest->getUpdated(), $date);
    }
}