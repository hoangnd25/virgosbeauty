<?php

namespace VB\CommerceBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;

/** @JMS\AccessType("public_method") */
class Cart {

    /**
     * @JMS\Type("ArrayCollection<VB\CommerceBundle\Model\CartItem>")
     */
    protected $items;

    function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function totalPrice(){
        $totalPrice = 0;
        /**
         * @var $item CartItem
         */
        foreach($this->items as $item){
            $totalPrice += $item->getTotalPrice();
        }
        return $totalPrice;
    }

    /**
     * @param mixed $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * @return ArrayCollection<CartItem>
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param mixed $item
     */
    public function addItem($item)
    {
        $this->items->add($item);
    }

    /**
     * @param mixed $item
     */
    public function removeItem($item)
    {
        $this->items->removeElement($item);
    }

} 