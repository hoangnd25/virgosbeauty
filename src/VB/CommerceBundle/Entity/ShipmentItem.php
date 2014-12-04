<?php

namespace VB\CommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="shipment_item")
 */
class ShipmentItem
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Shipment", inversedBy="requestItems")
     * @ORM\JoinColumn(name="shipment_id", referencedColumnName="id")
     */
    protected $shipment;

    /**
     * @ORM\OneToOne(targetEntity="ProductRequest")
     * @ORM\JoinColumn(name="request_id", referencedColumnName="id")
     */
    private $request;

    /**
     * @ORM\Column(type="integer",length=8, nullable=true)
     */
    protected $saleCommission;

    /**
     * @ORM\Column(type="decimal",length=8, nullable=true, precision=10, scale=2)
     */
    protected $inputPrice;

    /**
     * @ORM\Column(type="decimal",length=8, nullable=true, precision=10, scale=2)
     */
    protected $salePrice;

    function __construct($request = null, $shipment = null)
    {
        $this->setRequest($request);
        $this->setShipment($shipment);
    }

    function __toString()
    {
        return $this->getRequest()->getName();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Shipment
     */
    public function getShipment()
    {
        return $this->shipment;
    }

    /**
     * @param Shipment $shipment
     */
    public function setShipment($shipment)
    {
        $this->shipment = $shipment;
    }

    /**
     * @return ProductRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param ProductRequest $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
        if($request){
            $request->setStatus(ProductRequest::STATUS_SHIPPED);
        }
    }

    /**
     * @return mixed
     */
    public function getSaleCommission()
    {
        if($this->getRequest() && $this->getRequest()->getProduct()){
            return $this->saleCommission ? $this->saleCommission : $this->getRequest()->getProduct()->getSaleCommission();
        }else{
            return $this->saleCommission;
        }
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
    public function getInputPrice()
    {
        return $this->inputPrice;
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
    public function getSalePrice()
    {
        return $this->salePrice;
    }

    /**
     * @param mixed $salePrice
     */
    public function setSalePrice($salePrice)
    {
        $this->salePrice = $salePrice;
    }

}