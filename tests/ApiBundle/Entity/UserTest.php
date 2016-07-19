<?php
namespace Tests\ApiBundle\Entity;

use ApiBundle\Entity\User;

class UserTest extends \PHPUnit_Framework_Testcase
{

    public function testConstructor()
    {
        $user = new User();
        $this->assertInstanceOf('ApiBundle\Entity\User', $user);
        $this->assertInstanceOf('FOS\UserBundle\Model\User', $user);
    }
}