<?php

namespace VB\CommerceBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use \Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Product Info')
                ->add('name', null, array())
                ->add('categories', 'sonata_type_model', array(
                    'btn_add' =>false,
                    'multiple' => true,
                    'required' => false,
                    'attr' => array(
                        'style' => 'width:300px;'
                    )
                ))
                ->add('sku', null, array())
                ->add('visible', null, array(
                    'required' => false
                ))
                ->add('availableForSale', null, array(
                    'required' => false
                ))
                ->add('price','money',array(
                    'precision' => 0,
                    'currency' => 'VND',
                ))
                ->add('old_price','money',array(
                    'required' => false,
                    'precision' => 0,
                    'currency' => 'VND'
                ))
                ->add('inputPrice','money',array(
                    'currency' => 'AUD'
                ))
                ->add('inputSource',null,array())
                ->add('saleCommission','money',array(
                    'required' => false,
                    'precision' => 0,
                    'currency' => 'VND'
                ))
                ->add('weight',null,array())
                ->add('tagLine', null, array())
                ->add('shortDescription', 'textarea', array(
                    'required' => false,
                    'attr' => array(
                        'rows' => '4'
                    )))
                ->add('description', 'ckeditor', array())
                ->add('howToUse', 'ckeditor', array())
            ->end()
            ->with('SEO')
                ->add('seoTitle', null, array())
                ->add('seoDescription', 'textarea', array())
            ->end()
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('sku',null,array('label'=>'SKU'))
            ->add('name')
            ->add('categories')
            ->add('price')
            ->add('visible')
            ->add('availableForSale')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('sku',null,array('label'=>'SKU'))
            ->add('visible')
            ->add('availableForSale')
            ->add('price','money',array(
                'grouping' => true,
                'precision' => 0,
                'currency' => 'VND'
            ))
            ->add('oldPrice','money',array(
                'grouping' => true,
                'precision' => 0,
                'currency' => 'VND'
            ))
            ->add('categories')
        ;
    }

    public function prePersist($product) {
        $this->manageImage($product);
    }
    public function preUpdate($product) {
        $this->manageImage($product);
    }

    protected function manageImage($product){
    }
}