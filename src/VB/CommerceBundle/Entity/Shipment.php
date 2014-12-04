<?php

namespace VB\CommerceBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="shipment")
 */
class Shipment
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(length=160)
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="ShipmentItem", mappedBy="shipment", cascade={"persist"}, orphanRemoval=true)
     */
    protected $items;

    /**
     * @ORM\Column(type="decimal", scale = 2)
     */
    protected $conversionRate;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->conversionRate = 20000.0;
    }

    /**
     * @return mixed
     */
    public function getConversionRate()
    {
        return $this->conversionRate;
    }

    /**
     * @param mixed $conversionRate
     */
    public function setConversionRate($conversionRate)
    {
        $this->conversionRate = floatval($conversionRate);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param mixed $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * @param ShipmentItem $item
     */
    public function addItem($item)
    {
        $this->items->add($item);
        $item->setShipment($this);
    }

    /**
     * @param ShipmentItem $item
     */
    public function removeItem($item)
    {
        $this->items->removeElement($item);
        $item->setShipment(null);
        $item->getRequest()->setStatus(ProductRequest::STATUS_REQUESTED);
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

}