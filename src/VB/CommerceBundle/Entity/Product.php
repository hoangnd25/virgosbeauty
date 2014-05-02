<?php

namespace VB\CommerceBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use VB\CommerceBundle\Util\StringUtil;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity
 * @ORM\Table(name="product")
 * @JMS\AccessType("public_method")
 * @JMS\ExclusionPolicy("all")
 */
class Product
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
     * @ORM\Column(length=160)
     * @JMS\Expose()
     * @JMS\Type("string")
     */
    protected $name;

    /**
     * @ORM\Column(length=16)
     * @JMS\Expose()
     * @JMS\Type("string")
     */
    protected $sku;

    /**
     * @ORM\Column(length=160,nullable=true)
     * @JMS\Expose()
     * @JMS\Type("string")
     */
    protected $tagLine;

    /**
     * @ORM\Column(length=160)
     */
    protected $nameNonUnicode;

    /**
     * @ORM\Column(type="integer",length=8)
     * @JMS\Type("integer")
     * @JMS\Expose()
     */
    protected $price;

    /**
     * @ORM\Column(type="integer",length=8,nullable=true)
     */
    protected $oldPrice;

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(length=320,nullable=true)
     */
    protected $shortDescription;

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    protected $howToUse;

    /**
     * @ORM\ManyToMany(targetEntity="ProductCategory", mappedBy="products",cascade={"persist"})
     */
    protected $categories;

    /**
     * @Gedmo\Slug(fields={"nameNonUnicode"})
     * @ORM\Column(length=180, unique=true)
     * @JMS\Type("string")
     * @JMS\Expose()
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="ProductImage", mappedBy="product", orphanRemoval=true, cascade="persist")
     */
    protected $images;

    /**
     * @ORM\OneToMany(targetEntity="ProductProperty", mappedBy="product", orphanRemoval=true, cascade={"persist"})
     * @JMS\Type("array<VB\CommerceBundle\Entity\ProductProperty>")
     * @JMS\Expose()
     */
    protected $properties;

    /**
     * @ORM\OneToMany(targetEntity="ProductVariant", mappedBy="product", orphanRemoval=true)
     */
    protected $variants;

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
        $this->categories = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->properties = new ArrayCollection();
    }

    /**
     * @param mixed $categories
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }

    /**
     * @return mixed
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $howToUse
     */
    public function setHowToUse($howToUse)
    {
        $this->howToUse = $howToUse;
    }

    /**
     * @return mixed
     */
    public function getHowToUse()
    {
        return $this->howToUse;
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
     * @param mixed $images
     */
    public function setImages($images)
    {
        $this->images = $images;
    }

    /**
     * @return ArrayCollection<ProductImage>
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->nameNonUnicode = StringUtil::unicode_str_filter($name);
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
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
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $nameNonUnicode
     */
    public function setNameNonUnicode($nameNonUnicode)
    {
        $this->nameNonUnicode = $nameNonUnicode;
    }

    /**
     * @return mixed
     */
    public function getNameNonUnicode()
    {
        return $this->nameNonUnicode;
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
     * @param \VB\CommerceBundle\Entity\datetime $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * @return \VB\CommerceBundle\Entity\datetime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param mixed $tagLine
     */
    public function setTagLine($tagLine)
    {
        $this->tagLine = $tagLine;
    }

    /**
     * @return mixed
     */
    public function getTagLine()
    {
        return $this->tagLine;
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
        $property->setProduct($this);
    }

    /**
     * @param mixed $property
     */
    public function removeProperty($property)
    {
        $this->properties->removeElement($property);
        $property->setProduct(null);
    }

    /**
     * @param mixed $shortDescription
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;
    }

    /**
     * @return mixed
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * @param mixed $variants
     */
    public function setVariants($variants)
    {
        $this->variants = $variants;
    }

    /**
     * @return mixed
     */
    public function getVariants()
    {
        return $this->variants;
    }

    /**
     * @param mixed $variant
     */
    public function addVariant($variant)
    {
        $this->variants->add($variant);
        $variant->setProduct($this);
    }

    /**
     * @param mixed $variant
     */
    public function removeVariant($variant)
    {
        $this->variants->removeElement($variant);
        $variant->setProduct(null);
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