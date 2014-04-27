<?php

namespace VB\CommerceBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use VB\CommerceBundle\Entity\ProductProperty;
use VB\CommerceBundle\FormType\PropertyEditType;

class ProductPropertyController extends Controller
{

    /**
     * @param $id
     * @param $slug
     * @return array
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @Route("/p/property/{id}/edit",name="product_edit_property")
     * @Route("/p/{slug}property/add",name="product_add_property")
     */
    public function editAction($id=null,$slug=null){
        /**
         * @var $em EntityManager
         */
        $em = $this->getDoctrine()->getManager();

        if($id != null){
            $property = $em->getRepository('VBCommerceBundle:ProductProperty')->find($id);
            if(!$property)
                throw new NotFoundHttpException();
        }else{
            $property =  new ProductProperty();
        }

        $form = $this->createForm(new PropertyEditType(),$property ,array(
        ));

        if($this->getRequest()->getMethod() == 'POST'){
            $form->handleRequest($this->getRequest());
            if($form->isValid()){
                $formData = $form->getData();

                $values = $formData->getValues();
                foreach($values as $value){
                    $value->setProperty($property);
                }

                if($slug){
                    $formData->setProduct($em->getRepository('VBCommerceBundle:Product')->findOneBy(array(
                        'slug'=>$slug
                    )));
                }

                $em->persist($formData);
                $em->flush();
                if(!$slug){
                    return new RedirectResponse($this->generateUrl('product_edit_all_properties',array(
                        'slug'=>$property->getProduct()->getSlug()
                    )));
                }else{
                    return new RedirectResponse($this->generateUrl('product_edit_all_properties',array(
                        'slug'=>$slug
                    )));
                }
            }
        }

    }
}
