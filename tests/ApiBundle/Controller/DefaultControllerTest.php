<?php

namespace Tests\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertEquals('200', $client->getResponse()->getStatusCode());
        $this->assertEquals('application/json', $client->getResponse()->headers->get('content-type'));

        $client->request('GET','/.xml');
        $this->assertEquals('200', $client->getResponse()->getStatusCode());
        $this->assertContains('text/xml', $client->getResponse()->headers->get('content-type'));

        $client->request('POST','/');
        $this->assertEquals('405', $client->getResponse()->getStatusCode(), 'Assert that code is 405');
    }
}
