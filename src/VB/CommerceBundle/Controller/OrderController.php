<?php

namespace VB\CommerceBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use VB\CommerceBundle\FormType\OrderType;
use VB\CommerceBundle\Model\OrderStatus;
use VB\CommerceBundle\Entity\OrderItem;

class OrderController extends Controller
{
    /**
     * @Route("/order/submit",name="order_submit")
     * @Template()
     */
    function submitAction(){
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Home", $this->get("router")->generate("homepage"));
        $breadcrumbs->addItem('Giỏ hàng',$this->get("router")->generate("cart_show"));
        $breadcrumbs->addItem('Đặt hàng');

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

        $form = $this->createForm(new OrderType(), null, array(
            'label' =>false
        ));


        if($user = $this->getUser()){
            $form->get('contactName')->setData($user->getDisplayName());
            $form->get('phone')->setData($user->getPhone());
            $form->get('address')->setData($user->getAddress());
            $form->get('email')->setData($user->getEmail());
        }

        if($this->getRequest()->getMethod() == 'POST'){
            $form->handleRequest($this->getRequest());
            if($form->isValid()){
                $data = $form->getData();
                if($user = $this->getUser()){
                    $data->setUser($user);
                }
                $data->setStatus(OrderStatus::RECEIVED);

                $serializer = $this->get('jms_serializer');
                foreach($cart->getItems() as $item){
                    $orderItem = new OrderItem();
                    $orderItem->setOrder($data);
                    $orderItem->setPrice($item->getSinglePrice());
                    $orderItem->setQuantity($item->getAmount());
                    $serializedProduct = $serializer->serialize($item->getProduct(),'json');
                    $orderItem->setProduct($serializedProduct);

                    $data->getItems()->add($orderItem);
                }
                $em->persist($data);
                $em->flush();

                $this->get('cart')->clear();
                $this->get('session')->getFlashBag()->add('success','Đặt hàng thành công');

                return new RedirectResponse($this->generateUrl('cart_show'));
            }
        }

        return array('cart'=>$cart,'form'=>$form->createView());
    }


    /**
     * @Route("/order-management/received",name="order_received")
     * @Template()
     */
    function receivedOrderAction(){
        $em = $this->getDoctrine()->getManager();

        $orders = $em->getRepository('VBCommerceBundle:Order')->findBy(array(
            'status' => OrderStatus::RECEIVED
        ));

        $serializer = $this->get('jms_serializer');

        foreach($orders as $order){
            foreach($order->getItems() as $item){
                $product = $serializer->deserialize($item->getProduct(),'VB\CommerceBundle\Entity\Product','json');
                $item->setProduct($product);
            }
        }

        return array('orders'=>$orders);
    }

    /**
     * @Route("/order-management/processing",name="order_processing")
     * @Template()
     */
    function processingOrderAction(){
        $em = $this->getDoctrine()->getManager();

        $orders = $em->getRepository('VBCommerceBundle:Order')->findBy(array(
            'status' => OrderStatus::PROCESSING,
            'saleAssistant' => $this->getUser()
        ));

        $serializer = $this->get('jms_serializer');

        foreach($orders as $order){
            foreach($order->getItems() as $item){
                $product = $serializer->deserialize($item->getProduct(),'VB\CommerceBundle\Entity\Product','json');
                $item->setProduct($product);
            }
        }

        return array('orders'=>$orders);
    }

    /**
     * @Route("/order-management/finished",name="order_finished")
     * @Template()
     */
    function finishedOrderAction(){
        $em = $this->getDoctrine()->getManager();

        $orders = $em->getRepository('VBCommerceBundle:Order')->findBy(array(
            'status' => OrderStatus::FINISHED,
            'saleAssistant' => $this->getUser()
        ));

        $serializer = $this->get('jms_serializer');

        foreach($orders as $order){
            foreach($order->getItems() as $item){
                $product = $serializer->deserialize($item->getProduct(),'VB\CommerceBundle\Entity\Product','json');
                $item->setProduct($product);
            }
        }

        return array('orders'=>$orders);
    }

    /**
     * @Route("/order-management/canceled",name="order_canceled")
     * @Template()
     */
    function canceledOrderAction(){
        $em = $this->getDoctrine()->getManager();

        $orders = $em->getRepository('VBCommerceBundle:Order')->findBy(array(
            'status' => OrderStatus::CANCELED,
            'saleAssistant' => $this->getUser()
        ));

        $serializer = $this->get('jms_serializer');

        foreach($orders as $order){
            foreach($order->getItems() as $item){
                $product = $serializer->deserialize($item->getProduct(),'VB\CommerceBundle\Entity\Product','json');
                $item->setProduct($product);
            }
        }

        return array('orders'=>$orders);
    }

    /**
     * @Route("/order-management/order/{id}/process",name="order_process_order")
     */
    function processAction($id){
        $em = $this->getDoctrine()->getManager();

        $order = $em->getRepository('VBCommerceBundle:Order')->findOneBy(array(
            'id' => $id
        ));
        if(!$order)
            throw new NotFoundHttpException('Không tìm thấy đơn hàng');

        $order->setSaleAssistant($this->getUser());
        $order->setStatus(OrderStatus::PROCESSING);
        $em->persist($order);
        $em->flush();

        return new RedirectResponse($this->generateUrl('order_processing'));
    }

    /**
     * @Route("/order-management/order/{id}/finish",name="order_finish_order")
     * @Template()
     */
    function finishAction($id){
        $em = $this->getDoctrine()->getManager();

        $order = $em->getRepository('VBCommerceBundle:Order')->findOneBy(array(
            'id' => $id,
            'saleAssistant' => $this->getUser(),
            'status' => OrderStatus::PROCESSING
        ));
        if(!$order)
            throw new NotFoundHttpException('Không tìm thấy đơn hàng');

        $order->setStatus(OrderStatus::FINISHED);
        $em->persist($order);
        $em->flush();

        return new RedirectResponse($this->generateUrl('order_finished'));
    }

    /**
     * @Route("/order-management/order/{id}/cancel",name="order_cancel_order")
     * @Template()
     */
    function cancelAction($id){
        $em = $this->getDoctrine()->getManager();

        $order = $em->getRepository('VBCommerceBundle:Order')->findOneBy(array(
            'id' => $id,
            'saleAssistant' => $this->getUser(),
            'status' => OrderStatus::PROCESSING
        ));
        if(!$order)
            throw new NotFoundHttpException('Không tìm thấy đơn hàng');

        $order->setStatus(OrderStatus::CANCELED);
        $em->persist($order);
        $em->flush();

        return new RedirectResponse($this->generateUrl('order_canceled'));
    }

    /**
     * @Route("/order-management/order/{id}",name="order_show")
     * @Template()
     */
    function showAction($id){
        $em = $this->getDoctrine()->getManager();

        $order = $em->getRepository('VBCommerceBundle:Order')->findOneBy(array(
            'id' => $id
        ));
        if(!$order)
            throw new NotFoundHttpException('Không tìm thấy đơn hàng');

        $order->statusCode = $order->getStatus();
        $order->setStatus(OrderStatus::getName($order->getStatus()));
        $serializer = $this->get('jms_serializer');

        foreach($order->getItems() as $item){
            $product = $serializer->deserialize($item->getProduct(),'VB\CommerceBundle\Entity\Product','json');
            $item->setProduct($product);
        }

        return array('order'=>$order);
    }

    /**
     * @Route("/order-management/order-edit/{id}",name="order_edit")
     * @Template()
     */
    function editAction($id){
        $em = $this->getDoctrine()->getManager();

        $order = $em->getRepository('VBCommerceBundle:Order')->findOneBy(array(
            'id' => $id,
            'saleAssistant' => $this->getUser(),
            'status' => OrderStatus::PROCESSING
        ));
        if(!$order)
            throw new NotFoundHttpException('Không tìm thấy đơn hàng');

        $order->statusCode = $order->getStatus();
        $order->setStatus(OrderStatus::getName($order->getStatus()));
        $serializer = $this->get('jms_serializer');

        foreach($order->getItems() as $item){
            $product = $serializer->deserialize($item->getProduct(),'VB\CommerceBundle\Entity\Product','json');
            $item->setProduct($product);
        }

        return array('order'=>$order);
    }
}
