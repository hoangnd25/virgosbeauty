<?php

namespace VB\UserBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class UserAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('User details')
                ->add('username', null, array())
                ->add('email', 'email', array())
                ->add('displayName', null, array('required'=>false))
                ->add('enabled', null, array('required'=>false))
                ->add('locked', null, array('required'=>false))
                ->add('expired', null, array('required'=>false))
                ->add('lastLogin', 'datetime', array(
                    'disabled'=>true
                ))
                ->add('phone', null, array('required'=>false))
                ->add('address', 'textarea', array('required'=>false))
            ->end()
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('username')
            ->add('email')
            ->add('roles')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('username')
            ->add('email')
            ->add('enabled')
            ->add('displayName')
        ;
    }

    public function preUpdate($blog)
    {
    }
}