<?php

namespace VB\CommerceBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use VB\CommerceBundle\Entity\ProductRequest;

class ProductRequestAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
//            ->add('done', null, array(
//                'required' => false
//            ))
            ->add('product', null, array())
            ->add('newProduct', null, array())
            ->add('quantity', null, array())
            ->add('note', null, array())
            ->add('user', null, array())

            ->add('status', 'choice', array(
                'choices' => array(
                    null => '',
                    ProductRequest::STATUS_REQUESTED  => 'Requested',
                    ProductRequest::STATUS_SHIPPED => 'Shipped'
                )
            ))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('user')
            ->add('status', null, array(), 'choice', array(
                'choices' => array(
                    ProductRequest::STATUS_REQUESTED  => 'Requested',
                    ProductRequest::STATUS_SHIPPED => 'Shipped'
                )
            ))
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
//            ->add('id')
            ->add('product',null,array(
                'propertys' => 'name'
            ))
            ->add('newProduct')
            ->add('quantity')
            ->add('note')
            ->add('user')
            ->add('status')
        ;
    }

    /**
     * Add some fields from mapped entities; the simplest way;
     * @return array
     */
    public function getExportFields() {
        $fieldsArray = $this->getModelManager()->getExportFields($this->getClass());

        $fieldsArray[] = 'name';

        return $fieldsArray;
    }
}