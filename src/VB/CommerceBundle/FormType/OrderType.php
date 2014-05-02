<?php

namespace VB\CommerceBundle\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OrderType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contactName',null,array(
                'label' => 'Tên liên lạc',
                'widget_form_group_attr' => array(
                    'class' => 'form-group col-sm-12'
                )
            ))
            ->add('email','email',array(
                'widget_form_group_attr' => array(
                    'class' => 'form-group col-sm-6'
                )
            ))
            ->add('phone',null,array(
                'label' => 'Số điên thoại',
                'widget_form_group_attr' => array(
                    'class' => 'form-group col-sm-6'
                )
            ))
            ->add('address','textarea',array(
                'label' => 'Địa chỉ',
                'widget_form_group_attr' => array(
                    'class' => 'form-group col-sm-12'
                ),
                'attr' => array(
                    'rows' => 4
                )
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'VB\CommerceBundle\Entity\Order'
        ));
    }


    public function getName()
    {
        return 'order';
    }
}