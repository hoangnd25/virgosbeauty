<?php

namespace VB\CommerceBundle\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductVariationType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('variants','collection', array(
                'type' => new VariantType(),
                'label' => 'Variation',
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'widget_add_btn' => array('label' => 'add variant', 'attr' => array('class' => 'btn btn-success')),
                'options' => array(
                    'required'=>false,
                    'widget_remove_btn' => array('label' => 'remove variant', 'attr' => array('class' => 'btn btn-warning')),
                    'label_render' => false
                ),
                'attr'=>array(
                    'class' => 'variation-collection'
                )
            ))

            ->add('submit', 'submit', array(
                'attr' => array('class' => 'btn btn-info'),
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'VB\CommerceBundle\Entity\Product'
        ));
    }


    public function getName()
    {
        return 'product';
    }
}