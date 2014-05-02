<?php

namespace VB\CommerceBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity
 * @ORM\Table(name="product_property")
 * @JMS\AccessType("public_method")
 * @JMS\ExclusionPolicy("all")
 */
class ProductProperty
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
     * @ORM\Column(length=32)
     * @JMS\Type("string")
     * @JMS\Expose()
     */
    protected $name;

    /**
     * @ORM\Column(length=32)
     * @JMS\Type("string")
     * @JMS\Expose()
     */
    protected $code;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="properties")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;

    /**
     * @ORM\OneToMany(targetEntity="PropertyValue", mappedBy="property", cascade={"persist"}, orphanRemoval=true)
     * @JMS\Type("array<VB\CommerceBundle\Entity\PropertyValue>")
     * @JMS\Expose()
     */
    protected $values;

    /**
     * @var string
     *
     * @JMS\Expose()
     * @JMS\Type("string")
     */
    protected $serializedValue;

    public function __construct($code=null,$name=null,$product=null)
    {
        $this->code = $code;
        $this->name = $name;
        $this->setProduct($product);
        $this->values = new ArrayCollection();
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
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
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $product
     */
    public function setProduct($product)
    {
        if($product instanceof Product){
            $product->getProperties()->add($this);
        }
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
     * @param mixed $values
     */
    public function setValues($values)
    {
        $this->values = $values;
    }

    /**
     * @param mixed $value
     */
    public function addValue($value)
    {
        $this->values->add($value);
        $value->setProperty($this);
    }

    /**
     * @param mixed $value
     */
    public function removeValue($value)
    {
        $this->values->removeElement($value);
        $value->setProperty(null);
    }

    /**
     * @return mixed
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param string $serializedValue
     */
    public function setSerializedValue($serializedValue)
    {
        $this->serializedValue = $serializedValue;
    }

    /**
     * @return string
     */
    public function getSerializedValue()
    {
        return $this->serializedValue;
    }

}