<?php
namespace VB\CMSBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class RedirectRuleAdmin extends Admin
{
    protected $userManager;

    protected function configureRoutes(RouteCollection $collection)
    {
    }


    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('matchingUri')
            ->add('redirectUri')
            ->add('statusCode', 'choice', array(
                'choices' => array(
                    301 => 'Permanent',
                    302 => 'Temporary'
                )
            ))
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('matchingUri')
            ->add('redirectUri')
            ->add('statusCode')
        ;
    }

    public function getExportFields()
    {
        return array('matchingUri', 'redirectUri');
    }


    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('matchingUri')
            ->add('redirectUri')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'delete' => array(),
                    'edit' => array(),
                )
            ))
        ;
    }
}