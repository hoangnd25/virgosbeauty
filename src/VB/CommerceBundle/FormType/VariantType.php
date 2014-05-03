<?php

namespace VB\CommerceBundle\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class VariantType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sku',null,array(
                'widget_form_group_attr' => array(
                    'class' => 'form-group'
                ),
            ))
            ->add('price',null,array(
                'widget_form_group_attr' => array(
                    'class' => 'variant-price form-group'
                ),
            ))
            ->add('old_price',null,array(
                'widget_form_group_attr' => array(
                    'class' => 'variant-price form-group'
                ),
            ))
            ->add('properties','collection', array(
                'disabled' => true,
                'label' => 'Properties',
                'options' => array(
                    'required'=>false,
                    'label_render' => false,
                    'data_class' => 'VB\CommerceBundle\Entity\VariantProperty'
                ),
                'attr'=>array(
                    'class' => 'property-collection'
                )
            ))

        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'VB\CommerceBundle\Entity\ProductVariant'
        ));
    }


    public function getName()
    {
        return 'variant';
    }
}