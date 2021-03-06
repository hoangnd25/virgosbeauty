<?php

namespace VB\CommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="product_request")
 */
class ProductRequest
{
    const STATUS_SHIPPED = 'shipped';
    const STATUS_REQUESTED = 'requested';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;

    /**
     * @ORM\ManyToOne(targetEntity="VB\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\Column(length=160,nullable=true)
     */
    protected $newProduct;

    /**
     * @ORM\Column(type="integer",length=6)
     */
    protected $quantity;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $done;

    /**
     * @ORM\Column(length=20)
     */
    protected $status;

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    protected $note;

    public function __construct() {
        $this->done = false;
        $this->status = ProductRequest::STATUS_REQUESTED;
    }

    function __toString()
    {
        return $this->getName();
    }

    /**
     * @param mixed $newProduct
     */
    public function setNewProduct($newProduct)
    {
        $this->newProduct = $newProduct;
    }

    /**
     * @return mixed
     */
    public function getNewProduct()
    {
        return $this->newProduct;
    }

    /**
     * @param mixed $note
     */
    public function setNote($note)
    {
        $this->note = $note;
    }

    /**
     * @return mixed
     */
    public function getNote()
    {
        return $this->note;
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

    /**
     * @param mixed $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $done
     */
    public function setDone($done)
    {
        $this->done = $done;
    }

    /**
     * @return mixed
     */
    public function getDone()
    {
        return $this->done;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->getProduct() ? $this->getProduct()->getName() : $this->getNewProduct();
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }
}