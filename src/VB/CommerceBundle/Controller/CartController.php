<?php

namespace VB\CommerceBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use VB\CommerceBundle\Model\CartItem;

class CartController extends Controller
{
    /**
     * @param null $slug
     * @param int $amount
     * @return RedirectResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @Route("/add-to-cart/{slug}/{amount}",name="cart_add")
     * @Template()
     */
    public function addAction($slug = null, $amount = 1)
    {
        $request = $this->getRequest();
        $postData = $request->request->all();
        $slug = $slug ? $slug : $postData['slug'];
        $amount = intval($amount ? $amount : $postData['amount']);

        /**
         * @var $em EntityManager
         */
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('VBCommerceBundle:Product')->findOneBy(array('slug'=>$slug));
        if(!$product)
            throw new NotFoundHttpException();
        $productVariation = null;
        $imagePath = null;
//        $properties = array();

        if(array_key_exists('property',$postData)){
            $qb = $em->createQueryBuilder();
            $qb->select('v.id,COUNT(vp.variant) AS mycount')
                ->from('VBCommerceBundle:VariantProperty','vp')
                ->join('vp.variant','v')
                ->where('vp.propertyValue in (:property)')
                ->setParameter('property',array_values($postData['property']))
                ->groupBy('vp.variant')
                ->having('mycount = :totalProperties')
                ->setParameter('totalProperties',count($postData['property']))
                ;
            $qb->setMaxResults(1);
            $result = $qb->getQuery()->getResult();
            if($result){
                $variantId = $result[0]['id'];
                $productVariation = $em->getRepository('VBCommerceBundle:ProductVariant')->find($variantId);
            }

//            $qb = $em->createQueryBuilder();
//            $qb->select('pv')
//                ->from('VBCommerceBundle:PropertyValue','pv')
//                ->join('pv.property','p')
//                ->where('pv.id in (:ids)')
//                ->setParameter('ids',array_values($postData['property']))
//            ;
//            foreach( $qb->getQuery()->getResult() as $result){
//                $properties[$result->getProperty()->getName()] = $result->getValue();
//            }
        }else{

        }
        $productImage = $product->getImages()->first();
        if($productImage)
            $imagePath = $productImage->getFileName();

        $cartItem = new CartItem($product,$amount,$productVariation);
        $cartItem->setImagePath($imagePath);
//        $cartItem->setProperties($properties);

//        $this->get('cart')->clear();
        $this->get('cart')->add($cartItem);

//        ladybug_dump_die($this->get('cart')->get()->getItems()->first());
        $referer = $this->getRequest()->headers->get('referer');
        return new RedirectResponse($referer);
    }

    /**
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @Route("/cart/update/{slug}/{variant}",name="cart_update")
     */
    public function updateAction($slug,$variant=null){
        $postData = $this->getRequest()->request->all();
        $amount = intval($postData['amount']);
        $this->get('cart')->update($slug,$variant,$amount);
        $referer = $this->getRequest()->headers->get('referer');
        return new RedirectResponse($referer);
    }

    /**
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @Route("/cart/remove/{slug}/{variant}",name="cart_remove")
     */
    public function removeAction($slug,$variant=null){
        $this->get('cart')->removeBySlugAndVariant($slug,$variant);
        $referer = $this->getRequest()->headers->get('referer');
        return new RedirectResponse($referer);
    }


    /**
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @Route("cart",name="cart_show")
     * @Template()
     */
    public function showAction(){
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Home", $this->get("router")->generate("homepage"));
        $breadcrumbs->addItem('Giỏ hàng');

        $cart = $this->get('cart')->get();
        /**
         * @var $em EntityManager
         */
        $em = $this->getDoctrine()->getManager();
        foreach($cart->getItems() as $item){
            /**
             * @var $item CartItem
             */
            if($item->getProductVariation()){
                $variantion = $em->getRepository('VBCommerceBundle:ProductVariant')->find($item->getProductVariation()->getId());
                $item->setProductVariation($variantion);
            }
        }
        return array('cart'=>$cart);
    }

    /**
     *
     * @Template()
     */
    public function menuCartAction(){
        $cart = $this->get('cart')->get();
        /**
         * @var $em EntityManager
         */
        $em = $this->getDoctrine()->getManager();
        foreach($cart->getItems() as $item){
            /**
             * @var $item CartItem
             */
            if($item->getProductVariation()){
                $variantion = $em->getRepository('VBCommerceBundle:ProductVariant')->find($item->getProductVariation()->getId());
                $item->setProductVariation($variantion);
            }
        }
        return array('cart'=>$cart);
    }
}
