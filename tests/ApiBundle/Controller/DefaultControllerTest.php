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

        $client->request('GET', '/.xml');
        $this->assertEquals('200', $client->getResponse()->getStatusCode());
        $this->assertContains('text/xml', $client->getResponse()->headers->get('content-type'));

        $client->request('POST', '/');
        $this->assertEquals('405', $client->getResponse()->getStatusCode(), 'Assert that code is 405');
    }

    public function testLogin()
    {
        $client = static::createClient();


        $client->request('GET', $client->getContainer()->get('router')->generate('api_get_customerinforequests'));

        $this->assertEquals('401', $client->getResponse()->getStatusCode(),
            'Assert that user can\'t access protected page');

        $client->request('POST', '/login', [
            '_username' => 'test',
            '_password' => 'test'
        ]);

        $jwtToken = json_decode($client->getResponse()->getContent());
        $jwtToken = $jwtToken->token;

        $client->request('GET', $client->getContainer()->get('router')->generate('api_get_customerinforequests'),
            [],
            [],
            ['HTTP_Authorization' => 'Bearer '.$jwtToken]
            );

        $this->assertEquals('200', $client->getResponse()->getStatusCode(),
            'Assert that user can access protected page  after login');
    }
}
