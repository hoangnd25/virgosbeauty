<?php

namespace VB\CommerceBundle\Model;
use JMS\Serializer\Annotation as JMS;
use VB\CommerceBundle\Entity\ProductVariant;

/**
 * @JMS\AccessType("public_method")
 */
class CartItem {
    /**
     * @JMS\Type("VB\CommerceBundle\Entity\Product")
     */
    protected $product;

    /**
     * @JMS\Type("integer")
     */
    protected $amount;

    /**
     * @JMS\Type("VB\CommerceBundle\Entity\ProductVariant")
     */
    protected $productVariation;

//    /**
//     * @JMS\Type("array")
//     */
//    protected $properties;

    /**
     * @JMS\Type("string")
     */
    protected $imagePath;

    function __construct($product = null, $amount = null, $productVariation = null)
    {
        $this->amount = $amount;
        $this->product = $product;
        $this->productVariation = $productVariation;
    }

    public function getSinglePrice(){
        if($this->productVariation){
            $variationPrice = $this->productVariation->getPrice();
            if($variationPrice && $variationPrice>0)
                return $variationPrice;
        }
        return $this->getProduct()->getPrice();
    }

    public function getTotalPrice(){
        return $this->getAmount() * $this->getSinglePrice();
    }

//    /**
//     * @param mixed $properties
//     */
//    public function setProperties($properties)
//    {
//        $this->properties = $properties;
//    }
//
//    /**
//     * @return mixed
//     */
//    public function getProperties()
//    {
//        return $this->properties;
//    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param mixed $productVariation
     */
    public function setProductVariation($productVariation)
    {
        $this->productVariation = $productVariation;
    }

    /**
     * @return ProductVariant
     */
    public function getProductVariation()
    {
        return $this->productVariation;
    }

    /**
     * @param mixed $imagePath
     */
    public function setImagePath($imagePath)
    {
        $this->imagePath = $imagePath;
    }

    /**
     * @return mixed
     */
    public function getImagePath()
    {
        return $this->imagePath;
    }

} 