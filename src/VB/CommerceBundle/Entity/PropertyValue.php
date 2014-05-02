<?php

namespace VB\CommerceBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity
 * @ORM\Table(name="product_property_value")
 * @JMS\AccessType("public_method")
 * @JMS\ExclusionPolicy("all")
 */
class PropertyValue
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
     * @ORM\ManyToOne(targetEntity="ProductProperty", inversedBy="values")
     * @ORM\JoinColumn(name="property_id", referencedColumnName="id")
     */
    protected $property;

    /**
     * @ORM\OneToMany(targetEntity="VariantProperty", mappedBy="propertyValue", cascade={"all"})
     */
    protected $variantProperties;

    /**
     * @ORM\Column(length=80)
     * @JMS\Type("string")
     * @JMS\Expose()
     */
    protected $value;

    function __construct($value=null,$property=null)
    {
        $this->value = $value;
        $this->setProperty($property);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $property
     */
    public function setProperty($property)
    {
        if($property instanceof ProductProperty){
            if($property->getId() == null && is_array($property->getValues()))
                $property->setValues(new ArrayCollection($property->getValues()));
            $property->getValues()->add($this);
        }
        $this->property = $property;
    }

    /**
     * @return ProductProperty
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $variantProperties
     */
    public function setVariantProperties($variantProperties)
    {
        $this->variantProperties = $variantProperties;
    }

    /**
     * @return mixed
     */
    public function getVariantProperties()
    {
        return $this->variantProperties;
    }

}