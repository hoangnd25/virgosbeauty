<?php

namespace VB\CommerceBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use VB\CommerceBundle\Util\StringUtil;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity
 * @ORM\Table(name="product_variant")
 * @JMS\AccessType("public_method")
 * @JMS\ExclusionPolicy("all")
 */
class ProductVariant
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMS\Type("integer")
     * @JMS\Expose()
     */
    protected $id;

    /**
     * @ORM\Column(type="integer",length=8,nullable=true)
     * @JMS\Type("integer")
     * @JMS\Expose()
     */
    protected $price;

    /**
     * @ORM\Column(length=16,nullable=true)
     * @JMS\Expose()
     * @JMS\Type("string")
     */
    protected $sku;

    /**
     * @ORM\Column(type="integer",length=8,nullable=true)
     */
    protected $oldPrice;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="variants")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;

    /**
     * @ORM\OneToMany(targetEntity="VariantProperty", mappedBy="variant", orphanRemoval=true, cascade="all")
     *
     */
    protected $properties;

    public function __construct()
    {
        $this->properties = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $oldPrice
     */
    public function setOldPrice($oldPrice)
    {
        $this->oldPrice = $oldPrice;
    }

    /**
     * @return mixed
     */
    public function getOldPrice()
    {
        return $this->oldPrice;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param mixed $properties
     */
    public function setProperties($properties)
    {
        $this->properties = $properties;
    }

    /**
     * @return mixed
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param mixed $property
     */
    public function addProperty($property)
    {
        $this->properties->add($property);
        $property->setVariant($this);
    }

    /**
     * @param mixed $property
     */
    public function removeProperty($property)
    {
        $this->properties->removeElement($property);
        $property->setVariant(null);
    }

    /**
     * @param mixed $sku
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    /**
     * @return mixed
     */
    public function getSku()
    {
        return $this->sku;
    }

}