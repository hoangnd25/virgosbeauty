<?php

namespace VB\CommerceBundle\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PropertyType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code',null,array(
                'label' => 'Code',
                'widget_form_group_attr' => array(
                    'class' => 'property-code form-group'
                ),
            ))
            ->add('name',null,array(
                'label' => 'Name',
                'widget_form_group_attr' => array(
                    'class' => 'property-name form-group'
                ),
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
        return 'property';
    }
}