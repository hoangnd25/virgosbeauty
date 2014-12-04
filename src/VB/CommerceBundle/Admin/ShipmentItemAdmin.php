<?php

namespace VB\CommerceBundle\Admin;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use VB\CommerceBundle\Entity\ProductRequest;

class ShipmentItemAdmin extends Admin
{
    protected $doctrine;
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('request')
            ->add('saleCommission', null, array(
            ))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
//            ->add('name')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('request')
        ;
    }

    public function postRemove($item)
    {
        $request = $item->getRequest();
        $request->setStatus(ProductRequest::STATUS_REQUESTED);
        $this->getDoctrine()->getManager()->persist($request);
        $this->getDoctrine()->getManager()->flush();
    }



    /**
     * @return Registry
     */
    public function getDoctrine()
    {
        return $this->doctrine;
    }

    /**
     * @param Registry $doctrine
     */
    public function setDoctrine($doctrine)
    {
        $this->doctrine = $doctrine;
    }

}