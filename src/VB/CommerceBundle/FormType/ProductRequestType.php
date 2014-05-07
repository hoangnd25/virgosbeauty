<?php

namespace VB\CommerceBundle\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductRequestType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id','hidden',array(
                'mapped' =>false
            ))
            ->add('product','entity',array(
                'class' => 'VB\CommerceBundle\Entity\Product',
                'property' => 'name',
                'attr' => array(
                    'class' => 'chosen-select'
                ),
                'empty_value' => 'Chọn sản phẩm',
                'label' => 'Sản phẩm có sẵn',
                'required' => false
            ))
            ->add('newProduct','textarea',array(
                'label' => 'Sản phẩm mới (chỉ điền nếu yêu cầu sản phẩm không có trên website)',
                'required' => false
            ))
            ->add('quantity',null,array(
                'label' => 'Số lượng'
            ))
            ->add('note','textarea',array(
                'label' => 'Ghi chú',
                'required' => false
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'VB\CommerceBundle\Entity\ProductRequest'
        ));
    }


    public function getName()
    {
        return 'product_request';
    }
}