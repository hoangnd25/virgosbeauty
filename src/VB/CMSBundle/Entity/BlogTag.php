<?php

namespace VB\CMSBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="blog_tag")
 * @ORM\Entity()
 */
class BlogTag
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(length=20)
     */
    protected $name;


    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(length=20, unique=true)
     */
    private $slug;

    /**
     * @ORM\ManyToMany(targetEntity="Blog", mappedBy="tags", cascade="persist")
     */
    protected $blogs;

    public function __construct($name = null)
    {
        $this->setName($name);
        $this->blogs = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $blogs
     */
    public function setBlogs($blogs)
    {
        $this->blogs = $blogs;
    }

    /**
     * @return mixed
     */
    public function getBlogs()
    {
        return $this->blogs;
    }

    /**
     * @param mixed $blog
     */
    public function addBlog($blog)
    {
        $this->blogs->add($blog);
        $blog->getTags()->add($this);
    }

    /**
     * @param mixed $blog
     */
    public function removeTag($blog)
    {
        $this->blogs->removeElement($blog);
        $blog->getTags()->removeElement($this);
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


    function __toString()
    {
        return $this->getName();
    }


}