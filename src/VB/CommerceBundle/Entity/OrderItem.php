<?php

namespace VB\CommerceBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity
 * @ORM\Table(name="order_item")
 * @JMS\AccessType("public_method")
 * @JMS\ExclusionPolicy("all")
 */
class OrderItem
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="items")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     */
    protected $order;

    /**
     * @ORM\Column(type="integer",length=8)
     */
    protected $price;

    /**
     * @ORM\Column(type="integer",length=3)
     */
    protected $quantity;

    /**
     * @ORM\Column(type="text")
     */
    protected $product;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }


}