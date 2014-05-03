<?php

namespace VB\CMSBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use VB\CommerceBundle\Util\StringUtil;

/**
 * @ORM\Entity
 * @ORM\Table(name="blog")
 * @ORM\HasLifecycleCallbacks()
 */
class Blog
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer",length=9)
     */
    protected $pageViews;

    /**
     * @ORM\Column(length=160)
     */
    protected $title;

    /**
     * @Gedmo\Slug(fields={"titleNonUnicode"})
     * @ORM\Column(length=160, unique=true)
     */
    protected $slug;

    /**
     * @ORM\Column(length=160)
     */
    protected $titleNonUnicode;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $hidden;

    /**
     * @ORM\Column(type="text")
     */
    protected $body;

    /**
     * @ORM\Column(length=600)
     */
    protected $excerpt;

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

    /**
     * @ORM\ManyToMany(targetEntity="BlogTag", inversedBy="blogs",cascade={"persist"})
     * @ORM\JoinTable(name="blog_join_tag")
     */
    protected $tags;

    /**
     *
     * @ORM\Column(length=300,nullable=true)
     */
    protected $seoKeywords;

    /**
     *
     * @ORM\Column(length=600,nullable=true)
     */
    protected $seoDescription;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->hidden = false;
        $this->pageViews = 0;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
        $this->titleNonUnicode = StringUtil::unicode_str_filter($title);
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return mixed
     */
    public function getExcerpt()
    {
        return $this->excerpt;
    }

    /**
     * @param mixed $titleNonUnicode
     */
    public function setTitleNonUnicode($titleNonUnicode)
    {
        $this->titleNonUnicode = $titleNonUnicode;
    }

    /**
     * @return mixed
     */
    public function getTitleNonUnicode()
    {
        return $this->titleNonUnicode;
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @param mixed $tag
     */
    public function addTag($tag)
    {
        $this->tags->add($tag);
        $tag->getBlogs()->add($this);
    }

    /**
     * @param mixed $tag
     */
    public function removeTag($tag)
    {
        $this->tags->removeElement($tag);
        $tag->getBlogs()->removeElement($this);
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param \VB\CMSBundle\Entity\datetime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return \VB\CMSBundle\Entity\datetime
     */
    public function getCreated()
    {
        return $this->created;
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
     * @param \VB\CMSBundle\Entity\datetime $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * @return \VB\CMSBundle\Entity\datetime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param mixed $excerpt
     */
    public function setExcerpt($excerpt)
    {
        $this->excerpt = $excerpt;
    }

    /**
     * @param mixed $hidden
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
    }

    /**
     * @return mixed
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * @param mixed $seoDescription
     */
    public function setSeoDescription($seoDescription)
    {
        $this->seoDescription = $seoDescription;
    }

    /**
     * @return mixed
     */
    public function getSeoDescription()
    {
        return $this->seoDescription;
    }

    /**
     * @param mixed $seoKeywords
     */
    public function setSeoKeywords($seoKeywords)
    {
        $this->seoKeywords = $seoKeywords;
    }

    /**
     * @return mixed
     */
    public function getSeoKeywords()
    {
        return $this->seoKeywords;
    }

    /**
     * @param mixed $pageViews
     */
    public function setPageViews($pageViews)
    {
        $this->pageViews = $pageViews;
    }

    /**
     * @return mixed
     */
    public function getPageViews()
    {
        return $this->pageViews;
    }

    function __toString()
    {
        return $this->getTitle();
    }


}