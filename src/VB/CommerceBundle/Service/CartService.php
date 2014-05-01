<?php

namespace VB\CommerceBundle\Service;

use JMS\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Session\Session;
use VB\CommerceBundle\Entity\ProductVariant;
use VB\CommerceBundle\Model\Cart;
use VB\CommerceBundle\Model\CartItem;

class CartService {

    /**
     * @var $serializer Serializer
     */
    protected $serializer;

    /**
     * @var $session Session
     */
    protected $session;

    function __construct($serializer, $session)
    {
        $this->serializer = $serializer;
        $this->session = $session;
    }

    function add(CartItem $cartItem){
        $cart = $this->get();
        $duplicate = false;
        foreach($cart->getItems() as $item){
            $sameProduct = $item->getProduct()->getSlug() == $cartItem->getProduct()->getSlug();
            $sameVariation =  $this->checkSameVariant($item->getProductVariation(),$cartItem->getProductVariation());
            if($sameProduct && $sameVariation)
            {
                $duplicate = true;
                $item->setAmount($item->getAmount()+$cartItem->getAmount());
            }
        }
        if(!$duplicate){
            $cart->addItem($cartItem);
        }
        $this->save($cart);
    }

    private function checkSameVariant($inCartVariant,$compareVariant){
        if($compareVariant){
            if($inCartVariant){
                $sameVariation = $inCartVariant->getId() == $compareVariant->getId();
            }else{
                $sameVariation = false;
            }
        }else{
            $sameVariation = $inCartVariant? false :true;
        }
        return $sameVariation;
    }

    function update($slug,$variant,$amount){
        $cart = $this->get();
        foreach($cart->getItems() as $item){
            $sameProduct = $item->getProduct()->getSlug() == $slug;
            $testVariant =null;
            if($variant){
                $testVariant = new ProductVariant();
                $testVariant->setId($variant);
            }
            $sameVariation =  $this->checkSameVariant($item->getProductVariation(),$testVariant);

            if($sameProduct && $sameVariation)
            {
                if($amount!=0){
                    $item->setAmount($amount);
                }else{
//                    $this->remove($item);
                }
            }
        }
        $this->save($cart);
    }

    /**
     * @return Cart
     */
    function get(){
        $cart = $this->session->get('cart');
        if(!$cart){
            $this->save(new Cart());
            $cart = $this->session->get('cart');
        }
        return $this->deserialize($cart);
    }

    function clear(){
        $this->session->remove('cart');
    }

    function removeBySlugAndVariant($slug,$variant){
        $cart = $this->get();
        foreach($cart->getItems() as $item){
            $sameProduct = $item->getProduct()->getSlug() == $slug;
            $testVariant =null;
            if($variant){
                $testVariant = new ProductVariant();
                $testVariant->setId($variant);
            }
            $sameVariation =  $this->checkSameVariant($item->getProductVariation(),$testVariant);

            if($variant){
                if($item->getProductVariation()){
                    $sameVariation = $item->getProductVariation()->getId() == $variant;
                }else{
                    $sameVariation = false;
                }
            }
            if($sameProduct && $sameVariation)
            {
                $cart->getItems()->removeElement($item);
            }
        }
        $this->save($cart);
    }

    protected function save($cart){
        $this->session->set('cart',$this->serialize($cart));
    }

    protected function serialize($cart){
        return $this->serializer->serialize($cart,'json');
    }

    protected function deserialize($string){
        return $this->serializer->deserialize($string,'VB\CommerceBundle\Model\Cart','json');
    }
} 