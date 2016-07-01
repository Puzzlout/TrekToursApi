<?php
namespace Tests\ApiBundle\Repository;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use ApiBundle\Entity\CustomerInfoRequest;

class CustomerInfoRequestTest extends KernelTestCase
{

    /**
     * @var \Doctrine\Orm\EntityManager
     */
    private $entityManager;
    private $dateTimeNow;
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        self::bootKernel();
        $this->dateTimeNow = new \DateTime('now');
        $this->entityManager = static::$kernel->getContainer()->get('doctrine')->getManager();
        $tableName = $this->entityManager->getClassMetadata('ApiBundle:CustomerInfoRequest')->getTableName();
        $this->truncateTables($this->entityManager, [$tableName]);

        for($i=0; $i<10; $i++)
        {
            $dateTime = new \DateTime('now');
            $customerInfoRequest = new CustomerInfoRequest();
            $customerInfoRequest->setEmail('test'.$i.'@test.com');
            $customerInfoRequest->setFirstName('Test'.$i);
            $customerInfoRequest->setLastName('Test'.$i);
            $customerInfoRequest->setMessage('Test test '.$i);
            $customerInfoRequest->setPhoneNumber('+11122233344'.$i);
            $customerInfoRequest->setHasSentCopyToClient($i%2);
            $customerInfoRequest->setStatus(CustomerInfoRequest::STATUS_TBP);
            $this->entityManager->persist($customerInfoRequest);
            $customerInfoRequest->setCreated($dateTime->modify('+'.$i.' day'));
            $this->entityManager->flush();
        }

        $this->entityManager->flush();
    }

    public function testfindAllWithFilters()
    {
        $customerInfoRequests = $this->entityManager->getRepository('ApiBundle:CustomerInfoRequest')
            ->findAllWithFilters(0, 5);
        $this->assertCount(5, $customerInfoRequests['items']);
        $this->assertEquals(10, $customerInfoRequests['totalCount']);
        $customerInfoRequests = $this->entityManager->getRepository('ApiBundle:CustomerInfoRequest')
            ->findAllWithFilters(0, 2);
        $this->assertCount(2, $customerInfoRequests['items']);
        $customerInfoRequests = $this->entityManager->getRepository('ApiBundle:CustomerInfoRequest')
            ->findAllWithFilters(9, 10);
        $this->assertCount(1, $customerInfoRequests['items']);

        //test for current time interval
        $from = $this->dateTimeNow->format('Y-m-d');
        $to = $this->dateTimeNow->add(date_interval_create_from_date_string('30 days'))->format('Y-m-d');
        $customerInfoRequests = $this->entityManager->getRepository('ApiBundle:CustomerInfoRequest')
            ->findAllWithFilters(0, 5, $from, $to);
        $this->assertCount(5, $customerInfoRequests['items']);

        //check order of dates
        $this->assertGreaterThan($customerInfoRequests['items'][1]->getCreated()->getTimestamp(),
            $customerInfoRequests['items'][0]->getCreated()->getTimestamp());

        //test for previous time interval
        $to = $this->dateTimeNow->sub(date_interval_create_from_date_string('6 days'))->format('Y-m-d');
        $from = $this->dateTimeNow->sub(date_interval_create_from_date_string('6 days'))->format('Y-m-d');
        $customerInfoRequests = $this->entityManager->getRepository('ApiBundle:CustomerInfoRequest')
            ->findAllWithFilters(0, 5, $from, $to);
        $this->assertCount(0, $customerInfoRequests['items']);
    }

    protected function tearDown()
    {
        parent::tearDown();
        $tableName = $this->entityManager->getClassMetadata('ApiBundle:CustomerInfoRequest')->getTableName();
        $this->truncateTables($this->entityManager, [$tableName]);
        $this->entityManager->close();
        $this->entityManager = null;
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