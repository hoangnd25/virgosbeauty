<?php

namespace VB\CommerceBundle\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',null,array())
            ->add('price',null,array())
            ->add('old_price',null,array())
            ->add('tag_line',null,array())
            ->add('short_description',null,array())
            ->add('description','ckeditor',array())
            ->add('how_to_use','ckeditor',array())

            ->add('images','collection', array(
                'type' => 'file',
                'label' => ' Upload new image',
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'widget_add_btn' => array('label' => 'add', 'attr' => array('class' => 'btn btn-success')),
                'options' => array(
                    'required'=>false,
                    'widget_remove_btn' => array('label' => 'remove', 'attr' => array('class' => 'btn btn-warning remove-image-btn')),
                    'label_render' => false
                )
            ))

            ->add('submit', 'submit', array(
                'attr' => array('class' => 'cusmo-btn pull-right'),
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
        return 'vb_commercebundle_productform';
    }
}