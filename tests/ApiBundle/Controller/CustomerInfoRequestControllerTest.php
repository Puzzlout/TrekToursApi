<?php

namespace Tests\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use ApiBundle\Entity\CustomerInfoRequest;
use Symfony\Component\Validator\Constraints\DateTime;
use Translatable\Fixture\Type\Custom;

class CustomerInfoRequestControllerTest extends WebTestCase
{
    public function testCustomerInfoRequest()
    {
        $client = static::createClient();
        $format = '.json';
        $postEndpoint = $client->getContainer()->get('router')->generate('api_post_customerinforequests');
        $getAllEndpoint = $client->getContainer()->get('router')->generate('api_get_customerinforequests');


        /* Test post endpoint */
        $client->request('POST', $postEndpoint.$format,
            array(
                'email' => 'test@test.com',
                'first_name' => 'Tèst',
                'last_name' => 'TèstTèst',
                'phone_number' => '+111222333444',
                'message' => 'Test message'
            ));
        $postResponse = $client->getResponse();
        //check status code
        $this->assertEquals('201', $postResponse->getStatusCode(), 'Expected 201 got '.$postResponse->getStatusCode());
        //check if it is json
        $this->assertJson($postResponse->getContent(), 'Expected valid JSON result');
        //check content-type
        $this->assertEquals(
            'application/json',
            $postResponse->headers->get('content-type'),
            'Expected application/json got '.$postResponse->headers->get('content-type'));
        $postJsonResponse = json_decode($postResponse->getContent());
        $getEndpoint = $client->getContainer()->get('router')->generate('api_get_customerinforequest',
            array('id' => $postJsonResponse->id));
        $patchEndpoint = $client->getContainer()->get('router')->generate('api_patch_customerinforequests',
            array('id' => $postJsonResponse->id));
        //check location
        $this->assertEquals(
            $getEndpoint, $postResponse->headers->get('Location'),
            'Expected '.$getEndpoint.' got '.$postResponse->headers->get('Location'));

        /* Test Get All endpoint with limit set to 1 and offset set to new CustomerInfoRequest Id - 1 */
        $client->request('GET', $getAllEndpoint.$format.'?limit=1&offset='.($postJsonResponse->id-1));
        $getAllResponse = $client->getResponse();
        //check status code
        $this->assertEquals(
            '200',
            $getAllResponse->getStatusCode(),
            'Expected 200 got '.$getAllResponse->getStatusCode());
        //check if it is json
        $this->assertJson(
            $getAllResponse->getContent(),
            'Expected valid JSON result');
        //check content-type
        $this->assertEquals(
            'application/json',
            $getAllResponse->headers->get('content-type'),
            'Expected application/json got '.$getAllResponse->headers->get('content-type'));
        $getAllJsonResponse = json_decode($getAllResponse->getContent());
        //check if new CustomerInfoRequest is listed
        $this->assertEquals(
            $postJsonResponse->id,
            $getAllJsonResponse[0]->id,
            'Expected '.$postJsonResponse->id.' got '.$getAllJsonResponse[0]->id);

        $getAllDate = $getAllJsonResponse[0]->created;
        $newDate = new \DateTime($getAllDate);
        $fromDate = $newDate->modify('-1 day');
        $fromDate = $fromDate->format('Y-m-d');
        $toDate = $newDate->modify('+2 day');
        $toDate = $toDate->format('Y-m-d');
        /* Test get all with from and to */
        $client->request('GET', $getAllEndpoint.$format.'?from='.$fromDate.'&to='.$toDate);
        $getAllDateResponse = $client->getResponse();
        //check status code
        $this->assertEquals(
            '200',
            $getAllDateResponse->getStatusCode(),
            'Expected 200 got '.$getAllDateResponse->getStatusCode());
        //check if it is json
        $this->assertJson(
            $getAllDateResponse->getContent(),
            'Expected valid JSON result');
        //check content-type
        $this->assertEquals(
            'application/json',
            $getAllDateResponse->headers->get('content-type'),
            'Expected application/json got '.$getAllDateResponse->headers->get('content-type'));
        //check if anything is listed
        $this->assertGreaterThan(
            0,
            count(json_decode($getAllDateResponse->getContent())),
            'Expected result greater than 0');

        /* Test Get One endpoint by new CustomerInfoRequest Id */
        $client->request('GET', $getEndpoint.$format);
        $getResponse = $client->getResponse();
        //check status code
        $this->assertEquals(
            '200',
            $getResponse->getStatusCode(),
            'Expected 200 got '.$getResponse->getStatusCode());
        //check if it is json
        $this->assertJson(
            $getResponse->getContent(),
            'Expected valid JSON result');
        //check content-type
        $this->assertEquals(
            'application/json',
            $getResponse->headers->get('content-type'),
            'Expected application/json got '.$getResponse->headers->get('content-type'));
        $getJsonResponse = json_decode($getResponse->getContent());
        //check if new CustomerInfoRequest is listed
        $this->assertEquals(
            $postJsonResponse->id,
            $getJsonResponse->id,
            'Expected '.$postJsonResponse->id.' got '.$getJsonResponse->id);
        //check status
        $this->assertEquals(
            CustomerInfoRequest::STATUS_TBP, $getJsonResponse->status,
            'Expected '.CustomerInfoRequest::STATUS_TBP.' got '.$getJsonResponse->status);

        /* Test Patch Status */
        $client->request('PATCH', $patchEndpoint.$format,
            array(
                'status' => CustomerInfoRequest::STATUS_RTC
            ));
        $patchResponse = $client->getResponse();
        //check status code
        $this->assertEquals(
            '200',
            $patchResponse->getStatusCode(),
            'Expected 200 got '.$patchResponse->getStatusCode());
        //check if it is json
        $this->assertJson(
            $patchResponse->getContent(),
            'Expected valid JSON result');
        //check content-type
        $this->assertEquals(
            'application/json',
            $patchResponse->headers->get('content-type'),
            'Expected application/json got '.$patchResponse->headers->get('content-type'));
        $patchJsonResponse = json_decode($patchResponse->getContent());
        //check status
        $this->assertEquals(
            CustomerInfoRequest::STATUS_RTC, $patchJsonResponse->status,
            'Expected '.CustomerInfoRequest::STATUS_RTC.' got '.$patchJsonResponse->status);

        //truncate table
        $em = $client->getContainer()->get('doctrine')->getManager();
        $tableName = $em->getClassMetadata('ApiBundle:CustomerInfoRequest')->getTableName();
        $this->truncateTables($em, [$tableName], false);

    }

    /**
     * @param array $tables Name of the tables which will be truncated.
     * @param bool $cascade
     * @return void
     */
    private function truncateTables($em, $tables = array(), $cascade = false) {
        $connection = $em->getConnection();
        $platform = $connection->getDatabasePlatform();
        $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 0;');
        foreach ($tables as $name) {
            $connection->executeUpdate($platform->getTruncateTableSQL($name, $cascade));
            $connection->executeQuery('ALTER TABLE `'.$name.'` AUTO_INCREMENT = 1;');
        }
        $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 1;');
    }
}