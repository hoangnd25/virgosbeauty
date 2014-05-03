<?php

namespace VB\CMSBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class BlogAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Blog')
                ->add('title', 'text', array('label' => 'Title'))
                ->add('hidden', null, array(
                    'required' => false
                ))
                ->add('tags', 'sonata_type_model', array(
                    'multiple' => true,
                    'required' => false,
                    'attr' => array(
                        'style' => 'width:200px;'
                    )
                ))

                ->add('excerpt','textarea',array(
                    'required' => false,
                    'attr' => array(
                        'rows' => '4'
                    )
                ))
                ->add('body','ckeditor') //if no type is specified, SonataAdminBundle tries to guess it
            ->end()
            ->with('SEO')
                ->add('seoKeywords',null,array(
                    'required' => false
                ))
                ->add('seoDescription','textarea',array(
                    'required' => false,
                    'attr' => array(
                        'rows' => '4'
                    )
                ))
            ->end()
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('hidden')
            ->add('created')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('pageViews')
            ->add('tags','entity')
            ->add('created')
        ;
    }

    public function preUpdate($blog)
    {
    }
}