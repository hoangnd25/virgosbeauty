<?php

namespace VB\CommerceBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use VB\CommerceBundle\Util\StringUtil;

/**
 * @ORM\Entity
 * @ORM\Table(name="product_variant_property")
 */
class VariantProperty
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="PropertyValue")
     * @ORM\JoinColumn(name="property_value_id", referencedColumnName="id")
     */
    protected $propertyValue;

    /**
     * @ORM\ManyToOne(targetEntity="ProductVariant", inversedBy="properties")
     * @ORM\JoinColumn(name="variant_id", referencedColumnName="id")
     */
    protected $variant;

    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $variant
     */
    public function setVariant($variant)
    {
        $this->variant = $variant;
    }

    /**
     * @return mixed
     */
    public function getVariant()
    {
        return $this->variant;
    }

    /**
     * @param mixed $propertyValue
     */
    public function setPropertyValue($propertyValue)
    {
        $this->propertyValue = $propertyValue;
    }

    /**
     * @return mixed
     */
    public function getPropertyValue()
    {
        return $this->propertyValue;
    }

    function __toString()
    {
        $propertyValue = $this->getPropertyValue();
        return $propertyValue->getProperty()->getName().": ".$propertyValue->getValue();
    }


}