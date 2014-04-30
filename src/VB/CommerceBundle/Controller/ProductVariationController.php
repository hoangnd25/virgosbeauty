<?php

namespace VB\CommerceBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use VB\CommerceBundle\Entity\ProductProperty;
use VB\CommerceBundle\Entity\ProductVariant;
use VB\CommerceBundle\Entity\VariantProperty;
use VB\CommerceBundle\FormType\ProductVariationType;
use VB\CommerceBundle\FormType\PropertyAddType;
use VB\CommerceBundle\FormType\PropertyEditType;

class ProductVariationController extends Controller
{

    /**
     * @param $slug
     * @return array
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @Route("/p/{slug}/variation/generate/add",name="product_generate_variants")
     */
    public function generateAction($slug=null){
        /**
         * @var $em EntityManager
         */
        $em = $this->getDoctrine()->getManager();

        $product = $em->getRepository('VBCommerceBundle:Product')->findOneBy(array(
            'slug' => $slug
        ));
        if(!$product){
            throw new NotFoundHttpException();
        }

        $properties = $product->getProperties();

        $propertyValueArray = array();

        foreach($properties as $property){
            $propertyValueArray[] = $property->getValues()->toArray();
        }

        $combinations = $this->combinations($propertyValueArray);

        foreach($product->getVariants() as $variant){
            $em->remove($variant);
        }

        foreach($combinations as $combination){
            $variant = new ProductVariant();

            if(count($combination) == 1){
                $variantProperty = new VariantProperty();
                $variantProperty->setPropertyValue($combination);
                $variant->addProperty($variantProperty);
            }else{
                foreach($combination as $value){
                    $variantProperty = new VariantProperty();
                    $variantProperty->setPropertyValue($value);
                    $variant->addProperty($variantProperty);
                }
            }
            $variant->setProduct($product);
            $em->persist($variant);
        }

        $em->flush();
        return new RedirectResponse($this->generateUrl('product_edit_variation',array('slug'=>$product->getSlug())));
    }

    private function combinations($arrays, $i = 0)
    {
        if (!isset($arrays[$i])) {
            return array();
        }
        if ($i == count($arrays) - 1) {
            return $arrays[$i];
        }

        // get combinations from subsequent arrays
        $tmp = $this->combinations($arrays, $i + 1);

        $result = array();

        // concat each array from tmp with each element from $arrays[$i]
        foreach ($arrays[$i] as $v) {
            foreach ($tmp as $t) {

                $result[] = is_array($t) ?
                    array_merge(array($v), $t) :
                    array($v, $t);
            }
        }
        return $result;
    }

}
