<?php

namespace VB\CommerceBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use VB\CommerceBundle\Util\StringUtil;

/**
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="product_category")
 * @ORM\Entity(repositoryClass="Gedmo\Tree\Entity\Repository\NestedTreeRepository")
 */
class ProductCategory
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
     * @ORM\Column(length=160)
     */
    protected $nameNonUnicode;

    /**
     * @Gedmo\Slug(fields={"nameNonUnicode"})
     * @ORM\Column(length=180, unique=true)
     */
    private $slug;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer")
     */
    private $lft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    private $lvl;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer")
     */
    private $rgt;

    /**
     * @Gedmo\TreeRoot
     * @ORM\Column(name="root", type="integer", nullable=true)
     */
    private $root;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="ProductCategory", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="ProductCategory", mappedBy="parent", cascade={"persist"})
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;

    /**
     * @ORM\ManyToMany(targetEntity="Product", inversedBy="categories")
     * @ORM\JoinTable(name="product_join_category")
     */
    protected $products;

    public function __construct($name = null, ProductCategory $parent = null)
    {
        $this->setName($name);
        if($parent){
            $this->parent = $parent;
            $parent->children->add($this);
        }
        $this->children = new ArrayCollection();
        $this->products = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setParent(ProductCategory $parent = null)
    {
        $this->parent = $parent;
    }

    /**
     * @return ProductCategory
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->nameNonUnicode = StringUtil::unicode_str_filter($this->name);
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getNameWithLevel()
    {
        $prefix = '';
        $level = $this->lvl;
//        for($i=1;$i< $level,$i++;){
//            $prefix .= '--';
//        }
        for ($i = 1; $i < $level; $i++) {
            $prefix .= '--';
        }
        return $prefix.$this->name;
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
     * @param mixed $products
     */
    public function setProducts($products)
    {
        $this->products = $products;
    }

    /**
     * @return mixed
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @return mixed
     */
    public function getLvl()
    {
        return $this->lvl;
    }
}