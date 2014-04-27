<?php

namespace VB\CommerceBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use VB\CommerceBundle\Util\StringUtil;

/**
 * @ORM\Entity
 * @ORM\Table(name="product")
 */
class Product
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
     * @ORM\Column(length=160,nullable=true)
     */
    protected $tagLine;

    /**
     * @ORM\Column(length=160)
     */
    protected $nameNonUnicode;

    /**
     * @ORM\Column(type="integer",length=8)
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
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="ProductImage", mappedBy="product", orphanRemoval=true, cascade="persist")
     */
    protected $images;

    /**
     * @ORM\OneToMany(targetEntity="ProductProperty", mappedBy="product", orphanRemoval=true, cascade={"persist"})
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
     * @param mixed $images
     */
    public function setImages($images)
    {
        $this->images = $images;
    }

    /**
     * @return mixed
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

}