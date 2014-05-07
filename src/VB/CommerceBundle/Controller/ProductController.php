<?php

namespace VB\CommerceBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use VB\CommerceBundle\Entity\ProductImage;
use VB\CommerceBundle\Entity\ProductProperty;
use VB\CommerceBundle\FormType\ProductType;
use VB\CommerceBundle\FormType\ProductVariationType;
use VB\CommerceBundle\FormType\PropertyAddType;
use VB\CommerceBundle\FormType\PropertyEditType;
use VB\CommerceBundle\FormType\PropertyType;

class ProductController extends Controller
{
    /**
     * @param $category
     * @param null $parent
     * @param null $grandparent
     * @return array
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @Route("/c/{category}",name="product_by_category")
     * @Route("/c/{parent}/{category}",name="product_by_category_and_parent")
     * @Route("/c/{grandparent}/{parent}/{category}",name="product_by_category_and_grandparent")
     * @Template()
     */
    public function categoryAction($category,$parent=null,$grandparent=null)
    {
        /**
         * @var $em EntityManager
         */
        $em = $this->getDoctrine()->getManager();
        $repoCategory = $em->getRepository('VBCommerceBundle:ProductCategory');
        $category = $repoCategory->findOneBy(array('slug'=>$category));
        if(!$category)
            throw new NotFoundHttpException();
        $categoryChildren = $repoCategory->children($category);

        $products = null;
        $qb = $em->createQueryBuilder();
        $qb->select('p')
            ->from('VBCommerceBundle:Product','p')
            ->join('p.categories','c')
            ->where($qb->expr()->eq('p.visible',true))
            ->orderBy('p.id','desc');

        $categoryArray =  array();
        $categoryArray[] = $category->getId();
        if($categoryChildren){
            foreach($categoryChildren as $categoryChild){
                $categoryArray[] = $categoryChild->getId();
            }
        }

        $qb->add('where', $qb->expr()->in('c.id', $categoryArray));

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $qb->getQuery(),
            $this->get('request')->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );


        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Home", $this->get("router")->generate("homepage"));

        if($grandparent){
            $breadcrumbs->addItem
                ($category->getParent()->getParent()->getName(),
                $this->generateUrl('product_by_category',array('category'=>$grandparent))
            );
            $breadcrumbs->addItem
                ($category->getParent()->getName(),
                    $this->generateUrl('product_by_category_and_parent',array(
                        'category'=>$parent,
                        'parent'=>$grandparent
                    ))
            );
        }elseif($parent){
            $breadcrumbs->addItem
                ($category->getParent()->getName(),
                    $this->generateUrl('product_by_category',array('category'=>$parent))
                );
        }
        $breadcrumbs->addItem
            ($category->getName()
        );

        $categoryUrl = $this->getRequest()->getUri();
        $seoPage = $this->container->get('sonata.seo.page');
        $categoryName = ucwords(strtolower($category->getName())).' | Virgos Beauty';
        $seoPage
            ->setTitle($categoryName)
//            ->addMeta('name', 'description', $product->getShortDescription())
            ->addMeta('property', 'og:title',$categoryName )
            ->addMeta('property', 'og:url',  $categoryUrl)
//            ->addMeta('property', 'og:description', $product->getShortDescription())
            ->addMeta('property', 'fb:app_id', '1499107470317043')
        ;
//        if($firstImage = $product->getImages()->first()){
//            $imagePath = 'images/public/product_image/'.$firstImage->getFileName();
//            $imagePath = $this->get('liip_imagine.cache.manager')->getBrowserPath($imagePath, 'product_image_slideshow_thumb');
//            $seoPage->addMeta('property', 'og:image',  $imagePath);
//        }
        $seoPage->setLinkCanonical($categoryUrl);

        return array('products' => $pagination,'category'=>$category);
    }

    /**
     * @return array
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @Route("/p",name="product_all")
     * @Template()
     */
    public function allAction()
    {
        /**
         * @var $em EntityManager
         */
        $em = $this->getDoctrine()->getManager();

        $products = null;
        $qb = $em->createQueryBuilder();
        $qb->select('p')
            ->from('VBCommerceBundle:Product','p')
            ->where($qb->expr()->eq('p.visible',true))
            ->join('p.categories','c')
            ->leftJoin('p.images','i')
            ->orderBy('p.id','desc');

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $qb->getQuery(),
            $this->get('request')->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Home", $this->get("router")->generate("homepage"));
        $breadcrumbs->addItem("Sản phẩm");
        return array('products' => $pagination,'category'=>null);
    }

    /**
     * @param $slug
     * @return array
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @Route("/p/{slug}",name="product_by_slug")
     * @Template()
     */
    public function showAction($slug){
        /**
         * @var $em EntityManager
         */
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('VBCommerceBundle:Product')->findOneBy(array(
            'slug'=>$slug,
            'visible' => true
        ));
        if(!$product)
            throw new NotFoundHttpException();

        $qb = $em->createQueryBuilder();
        $qb->select('p')
            ->from('VBCommerceBundle:ProductProperty','p')
            ->join('p.values','v')
            ->where('p.product = :product')
            ->setParameter('product',$product);
        $property = $qb->getQuery()->getResult();
        $product->setProperties($property);

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Home", $this->get("router")->generate("homepage"));

        $breadcrumbs->addItem
            ($product->getCategories()->first()->getName(),
                $this->generateUrl('product_by_category',array('category'=>$product->getCategories()->first()->getSlug()))
            );
        $breadcrumbs->addItem
            ($product->getName()
            );

        $productUrl = $this->generateUrl('product_by_slug',array('slug'=>$product->getSlug()),true);
        $seoPage = $this->container->get('sonata.seo.page');
        $productName = ucwords(strtolower($product->getName())).' | Virgos Beauty';
        $seoPage
            ->setTitle($productName)
            ->addMeta('name', 'description', $product->getShortDescription())
            ->addMeta('property', 'og:title',$productName )
            ->addMeta('property', 'og:type', 'product')
            ->addMeta('property', 'og:url',  $productUrl)
            ->addMeta('property', 'og:description', $product->getShortDescription())
            ->addMeta('property', 'fb:app_id', '1499107470317043')
            ->addMeta('property', 'product:price:amount',  $product->getPrice())
            ->addMeta('property', 'product:price:currency',  'VND')
        ;
        if($product->getOldPrice()){
            $seoPage
            ->addMeta('property', 'product:original_price:amount',  $product->getOldPrice())
            ->addMeta('property', 'product:original_price:currency',  'VND');
        }
        if($firstImage = $product->getImages()->first()){
            $imagePath = 'images/public/product_image/'.$firstImage->getFileName();
            $imagePath = $this->get('liip_imagine.cache.manager')->getBrowserPath($imagePath, 'product_image_slideshow_thumb');
            $seoPage->addMeta('property', 'og:image',  $imagePath);
        }
        $seoPage->setLinkCanonical($productUrl);
        return array('product'=>$product);
    }

    /**
     * @param $slug
     * @return array
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @Route("/p/{slug}/edit",name="product_edit_by_slug")
     * @Template()
     */
    public function editAction($slug){
        /**
         * @var $em EntityManager
         */
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('VBCommerceBundle:Product')->findOneBy(array(
            'slug'=>$slug
        ));
        if(!$product)
            throw new NotFoundHttpException();

        $productImages = $em->getRepository('VBCommerceBundle:ProductImage')->findBy(array(
            'product'=>$product
        ));

        $product->setImages(null);

        $form = $this->createForm(new ProductType(), $product, array(
        ));
        if($this->getRequest()->getMethod() == 'POST'){
            $form->handleRequest($this->getRequest());
            if ($form->isValid()) {
                $formData = $form->getData();
                $images = $formData->getImages();
                $formData->setImages(new ArrayCollection());
                foreach ($images as $item) {
                    if ($item instanceof UploadedFile) {
                        $image = new ProductImage($item);
                        $image->setProduct($formData);
                        $formData->getImages()->add($image);
                    }
                }

                $properties = $formData->getProperties();
                foreach($properties as $property){
                    $property->setProduct($formData);
                }

                $em->persist($formData);
                $em->flush();
                return new RedirectResponse($this->generateUrl('product_by_slug',array('slug'=>$product->getSlug())));
            }
        }

        $product->setImages($productImages);

        return array('form'=>$form->createView(),'product'=>$product);
    }

    /**
     * @param $categorySlug
     * @return array
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @Route("/p/category/{categorySlug}/add",name="product_add_by_category")
     * @Template()
     */
    public function addByCategoryAction($categorySlug){
        /**
         * @var $em EntityManager
         */
        $em = $this->getDoctrine()->getManager();

        $category = $em->getRepository('VBCommerceBundle:ProductCategory')->findOneBy(array(
            'slug' => $categorySlug
        ));
        if(!$category)
            throw new NotFoundHttpException();

        $form = $this->createForm(new ProductType(),null ,array(
        ));

        if($this->getRequest()->getMethod() == 'POST'){
            $form->handleRequest($this->getRequest());
            if ($form->isValid()) {
                $formData = $form->getData();
                $images = $formData->getImages();
                $formData->setImages(new ArrayCollection());
                foreach ($images as $item) {
                    if ($item instanceof UploadedFile) {
                        $image = new ProductImage($item);
                        $image->setProduct($formData);
                        $formData->getImages()->add($image);
                    }
                }
                $category->getProducts()->add($formData);
                $properties = $formData->getProperties();
                foreach($properties as $property){
                    $property->setProduct($formData);
                }
                $em->persist($formData);
                $em->flush();
                return new RedirectResponse($this->generateUrl('product_by_slug',array('slug'=>$formData->getSlug())));
            }
        }

        return array('form'=>$form->createView(),'category'=>$category);
    }

    /**
     * @param $slug
     * @return array
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @Route("/p/{slug}/properties/edit",name="product_edit_all_properties")
     * @Template()
     */
    public function editPropertiesAction($slug){
        /**
         * @var $em EntityManager
         */
        $em = $this->getDoctrine()->getManager();

        $product = $em->getRepository('VBCommerceBundle:Product')->findOneBy(array(
            'slug' => $slug
        ));
        if(!$product)
            throw new NotFoundHttpException();

        $forms = array();
        foreach($product->getProperties() as $property){
            $form = $this->createForm(new PropertyEditType(),$property ,array(
                'action' => $this->generateUrl('product_edit_property',array('id'=>$property->getId())),
                'method' => 'POST'
            ));
            $forms[] = $form->createView();
        }
        $addPropertyForm  = $this->createForm(new PropertyAddType(),new ProductProperty() ,array(
            'action' => $this->generateUrl('product_add_property',array('slug'=>$slug)),
            'method' => 'POST'
        ));

        return array('forms'=>$forms,'product'=>$product,
            'addPropertyForm'=>$addPropertyForm->createView());
    }

    /**
     * @param $slug
     * @return array
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @Route("/p/{slug}/variation/edit",name="product_edit_variation")
     * @Template()
     */
    public function editVariantsAction($slug=null){
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

        $form = $this->createForm(new ProductVariationType(),$product ,array(
        ));

        if($this->getRequest()->getMethod() == 'POST'){
            $form->handleRequest($this->getRequest());
            if($form->isValid()){
                $formData = $form->getData();

                $em->persist($formData);
                $em->flush();

                return new RedirectResponse($this->generateUrl('product_edit_variation',array(
                    'slug'=>$product->getSlug()
                )));
            }
        }
        return array('form'=>$form->createView(),'product'=>$product);
    }
}
