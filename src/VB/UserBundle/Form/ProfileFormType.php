<?php

namespace VB\UserBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;

class ProfileFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        // add your custom field
        $builder->add('displayName',null,array(
            'label' => 'Tên hiển thị'
        ));
        $builder->add('phone',null,array(
            'label' => 'Số điện thoại'
        ));
        $builder->add('address','textarea',array(
            'label' => 'Địa chỉ'
        ));
    }

    public function getName()
    {
        return 'vb_user_profile';
    }
}