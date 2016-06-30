<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * CustomerInfoRequest
 *
 * @ORM\Entity
 * @ORM\Table(name="customer_info_request")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\CustomerInfoRequest")
 */
class CustomerInfoRequest
{
    const STATUS_TBP = 'TBP';
    const STATUS_RTC = 'RTC';
    const STATUS_RQC = 'RQC';

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"list", "details"})
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=false)
     * @Serializer\Groups({"list", "details"})
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=100)
     * @Serializer\Groups({"list", "details"})
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=100)
     * @Serializer\Groups({"list", "details"})
     */
    private $lastName;

    /**
     * @var integer
     *
     * @ORM\Column(name="phone_number", type="string", nullable=true)
     * @Serializer\Groups({"details"})
     */
    private $phoneNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text")
     * @Serializer\Groups({"details"})
     */
    private $message;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", nullable=false, length=5)
     * @Serializer\Groups({"list", "details"})
     */
    protected $status;

    /**
     * @var boolean
     *
     * @ORM\Column(name="has_sent_copy_to_client", type="boolean")
     * @Serializer\Groups({"details"})
     */
    private $hasSentCopyToClient;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     * @Gedmo\Timestampable(on="create")
     * @Serializer\Groups({"list", "details"})
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated", type="datetime")
     * @Gedmo\Timestampable(on="update")
     * @Serializer\Groups({"list", "details"})
     */
    protected $updated;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return CustomerInfoRequest
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return CustomerInfoRequest
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return CustomerInfoRequest
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set phoneNumber
     *
     * @param integer $phoneNumber
     *
     * @return CustomerInfoRequest
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return integer
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return CustomerInfoRequest
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return CustomerInfoRequest
     */
    public function setStatus($status)
    {
        if($status == self::STATUS_TBP || $status == self::STATUS_RQC || $status == self::STATUS_RTC) {
            $this->status = $status;
        }

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return CustomerInfoRequest
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return CustomerInfoRequest
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set hasSentCopyToClient
     *
     * @param boolean $hasSentCopyToClient
     *
     * @return CustomerInfoRequest
     */
    public function setHasSentCopyToClient($hasSentCopyToClient)
    {
        $this->hasSentCopyToClient = $hasSentCopyToClient;

        return $this;
    }

    /**
     * Get hasSentCopyToClient
     *
     * @return boolean
     */
    public function getHasSentCopyToClient()
    {
        return $this->hasSentCopyToClient;
    }
}
