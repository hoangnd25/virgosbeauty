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
            ->with('Data')
                ->add('name', null, array())
                ->add('visible', null, array(
                    'required' => false
                ))
                ->add('availableForSale', null, array(
                    'required' => false
                ))
                ->add('categories', 'sonata_type_model', array(
                    'btn_add' =>false,
                    'multiple' => true,
                    'required' => false,
                    'attr' => array(
                        'style' => 'width:300px;'
                    )
                ))
                ->add('sku', null, array())
                ->add('price', null, array())
                ->add('oldPrice', null, array())
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
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('sku',null,array('label'=>'SKU'))
            ->add('price')
            ->add('oldPrice')
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