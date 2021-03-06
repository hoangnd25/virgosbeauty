<?php

namespace VB\CommerceBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use VB\CommerceBundle\Util\StringUtil;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
     * @ORM\Column(length=16,unique=true)
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
     * @ORM\Column(type="decimal",length=8, nullable=true, precision=10, scale=2)
     */
    protected $inputPrice;

    /**
     * @ORM\Column(length=80, nullable=true)
     */
    protected $inputSource;

    /**
     * @ORM\Column(type="integer",length=8, nullable=true)
     * @JMS\Type("integer")
     * @JMS\Expose()
     */
    protected $saleCommission;

    /**
     * @ORM\Column(type="integer", length=4, nullable=true)
     */
    protected $weight;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $visible;

    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    protected $availableForSale;

    /**
     * @ORM\Column(type="integer",length=8,nullable=true)
     */
    protected $oldPrice;

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(length=400,nullable=true)
     */
    protected $shortDescription;

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    protected $howToUse;

    /**
     * @ORM\ManyToMany(targetEntity="ProductCategory", inversedBy="products",cascade={"persist"})
     * @ORM\JoinTable(name="product_join_category")
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
     * @ORM\Column(length=160,nullable=true)
     */
    protected $seoTitle;

    /**
     * @ORM\Column(length=400,nullable=true)
     */
    protected $seoDescription;

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
        $this->visible = true;
        $this->availableForSale = true;
    }

    public function __toString() {
        return $this->name;
    }
    /**
     * @param mixed $categories
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
        foreach($categories as $category){
            /**
             * @var $category ProductCategory
             */
            $category->getProducts()->add($this);
        }
    }

    public function addCategory($category){
        $this->getCategories()->add($category);
        $category->getProducts()->add($this);
    }

    public function removeCategory($category){
        $this->getCategories()->removeElement($category);
        $category->getProducts()->removeElement($this);
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

    /**
     * @param mixed $availableForSale
     */
    public function setAvailableForSale($availableForSale)
    {
        $this->availableForSale = $availableForSale;
    }

    /**
     * @return mixed
     */
    public function getAvailableForSale()
    {
        return $this->availableForSale;
    }

    /**
     * @param mixed $visible
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;
    }

    /**
     * @return mixed
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * @param mixed $inputPrice
     */
    public function setInputPrice($inputPrice)
    {
        $this->inputPrice = $inputPrice;
    }

    /**
     * @return mixed
     */
    public function getInputPrice()
    {
        return $this->inputPrice;
    }

    /**
     * @param mixed $inputSource
     */
    public function setInputSource($inputSource)
    {
        $this->inputSource = $inputSource;
    }

    /**
     * @return mixed
     */
    public function getInputSource()
    {
        return $this->inputSource;
    }

    /**
     * @param mixed $saleCommission
     */
    public function setSaleCommission($saleCommission)
    {
        $this->saleCommission = $saleCommission;
    }

    /**
     * @return mixed
     */
    public function getSaleCommission()
    {
        return $this->saleCommission;
    }

    /**
     * @param mixed $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * @return mixed
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @return mixed
     */
    public function getSeoTitle()
    {
        if($this->seoDescription){
            return $this->seoTitle;
        }else{
            if($this->categories->first()){
                return ucwords(strtolower($this->name)).' - '.ucfirst($this->categories->first()->getName());
            }else{
                return ucwords(strtolower($this->name));
            }
        }
    }

    /**
     * @param mixed $seoTitle
     */
    public function setSeoTitle($seoTitle)
    {
        $this->seoTitle = $seoTitle;
    }

    /**
     * @return mixed
     */
    public function getSeoDescription()
    {
        if($this->seoDescription){
            return $this->seoDescription;
        }else{
            return $this->tagLine.'. '.ucfirst($this->shortDescription);
        }
    }

    /**
     * @param mixed $seoDescription
     */
    public function setSeoDescription($seoDescription)
    {
        $this->seoDescription = $seoDescription;
    }

}