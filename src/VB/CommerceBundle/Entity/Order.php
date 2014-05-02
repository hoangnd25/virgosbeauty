<?php

namespace VB\CommerceBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity
 * @ORM\Table(name="orders")
 * @JMS\AccessType("public_method")
 * @JMS\ExclusionPolicy("all")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(length=80)
     */
    protected $contactName;

//    /**
//     * @ORM\ManyToOne(targetEntity="OrderStatus", inversedBy="orders")
//     * @ORM\JoinColumn(name="status_code", referencedColumnName="code")
//     */

    /**
     * @ORM\ManyToOne(targetEntity="VB\UserBundle\Entity\User", inversedBy="orders")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id",nullable = true)
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="VB\UserBundle\Entity\User", inversedBy="processingOrders")
     * @ORM\JoinColumn(name="sale_assistant_id", referencedColumnName="id",nullable = true)
     */
    protected $saleAssistant;

    /**
     * @ORM\Column(length=16)
     */
    protected $status;

    /**
     * @ORM\Column(length=80,nullable=true)
     */
    protected $email;

    /**
     * @ORM\Column()
     */
    protected $phone;

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    protected $address;

    /**
     * @ORM\OneToMany(targetEntity="OrderItem", mappedBy="order", orphanRemoval=true, cascade="persist")
     */
    protected $items;

    /**
     * @var datetime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var datetime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $contactName
     */
    public function setContactName($contactName)
    {
        $this->contactName = $contactName;
    }

    /**
     * @return mixed
     */
    public function getContactName()
    {
        return $this->contactName;
    }

    /**
     * @param \VB\CommerceBundle\Entity\datetime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return \VB\CommerceBundle\Entity\datetime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param \datetime $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * @return \datetime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $saleAssistant
     */
    public function setSaleAssistant($saleAssistant)
    {
        $this->saleAssistant = $saleAssistant;
    }

    /**
     * @return mixed
     */
    public function getSaleAssistant()
    {
        return $this->saleAssistant;
    }

    public function getTotalPrice(){
        $total = 0;
        foreach($this->getItems() as $item){
            $total += $item->getQuantity() * $item->getPrice();
        }
        return $total;
    }

}