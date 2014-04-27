<?php

namespace VB\CommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @ORM\Table(name="product_image")
 * @Vich\Uploadable
 */
class ProductImage
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Assert\File(
     *     maxSize="2M",
     *     mimeTypes={"image/png", "image/jpeg", "image/jpg"},     *
     *     mimeTypesMessage = "Please upload a valid image. Supported formats: JPG or PNG. Maximum size: 2 MB."
     * )
     * @Vich\UploadableField(mapping="product_image", fileNameProperty="fileName")
     *
     * @var File $file
     */
    protected $file;

    /**
     * @ORM\Column(type="string", length=255, name="image_name")
     *
     * @var string $fileName
     */
    protected $fileName;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime $updatedAt
     */
    protected $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="images")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;

    public function __construct(\Symfony\Component\HttpFoundation\File\UploadedFile $file = null) {
        $this->setFile($file);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\File $file
     */
    public function setFile($file)
    {
        $this->file = $file;
        if ($this->file) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param string $fileName
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }


}