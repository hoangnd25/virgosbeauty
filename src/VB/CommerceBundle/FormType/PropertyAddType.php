<?php

namespace VB\CommerceBundle\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PropertyAddType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code',null,array(
                'label' => 'Code',
                'widget_form_group_attr' => array(
                    'class' => 'property-code form-group col-sm-6'
                ),
            ))
            ->add('name',null,array(
                'label' => 'Name',
                'widget_form_group_attr' => array(
                    'class' => 'property-name form-group col-sm-6'
                ),
            ))
            ->add('values','collection', array(
                'type' => new PropertyValueType(),
                'label' => 'Value',
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'widget_add_btn' => array('label' => 'add', 'attr' => array('class' => 'btn btn-success')),
                'options' => array(
                    'required'=>false,
                    'widget_remove_btn' => array('label' => 'remove', 'attr' => array('class' => 'btn btn-warning remove-value-btn')),
                    'label_render' => false
                ),
                'attr'=>array(
                    'class' => 'value-collection'
                ),
                'widget_form_group_attr' => array(
                    'class' => 'col-sm-12'
                ),
            ))
            ->add('save','submit',array(
                'attr'=>array(
                    'style' => 'margin-left:20px'
                )
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'VB\CommerceBundle\Entity\ProductProperty'
        ));
    }


    public function getName()
    {
        return 'property_add';
    }
}