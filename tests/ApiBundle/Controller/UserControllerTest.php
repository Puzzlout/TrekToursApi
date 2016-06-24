<?php

namespace Tests\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{

    public function testGetMe()
    {
        $client = static::createClient();

        $endPoint = $client->getContainer()->get('router')->generate('api_get_me');

        /* Login required */
        $client->request('POST', '/login', [
            '_username' => 'test',
            '_password' => 'test'
        ]);
        $loginResponse = json_decode($client->getResponse()->getContent());
        $loginToken = $loginResponse->token;
        $jwtHeader = ['HTTP_Authorization' => 'Bearer '.$loginToken];

        $client->request('GET', $endPoint, [], [], $jwtHeader);
        $getMeResponse = $client->getResponse();

        $this->assertEquals(
            '200',
            $getMeResponse->getStatusCode(),
            'Expected 200 got '.$getMeResponse->getStatusCode());

        $this->assertJson(
            $getMeResponse->getContent(),
            'Expected valid JSON result');

        $this->assertEquals(
            'application/json',
            $getMeResponse->headers->get('content-type'),
            'Expected application/json got '.$getMeResponse->headers->get('content-type'));
        $profile = json_decode($getMeResponse->getContent());

        $this->assertObjectHasAttribute('roles', $profile);

        $this->assertObjectHasAttribute('username', $profile);
    }
}
