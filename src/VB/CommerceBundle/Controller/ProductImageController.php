<?php

namespace VB\CommerceBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ProductImageController extends Controller
{
    /**
     * @return Response
     * @param $image
     * @Route("/p/image/{id}/delete",name="product_image_delete")
     * @ParamConverter("image", class="VBCommerceBundle:ProductImage")
     */
    public function deleteAction($image)
    {
        /**
         * @var $em EntityManager
         */
        $em = $this->getDoctrine()->getManager();
        $product = $image->getProduct();
        $id = $image->getId();

        $em->remove($image);
        $em->flush();

        if($this->getRequest()->isXmlHttpRequest()){
            $result =  array(
                'success'=>true,
                'id' => $id
            );
            return new JsonResponse($result);
        }else{
            return new RedirectResponse($this->generateUrl('product_edit_by_slug',array('slug'=>$product->getSlug())));
        }
    }
}
