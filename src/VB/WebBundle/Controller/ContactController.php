<?php

namespace VB\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ContactController extends Controller
{
    /**
     * @Route("/contact-us",name="contact_us")
     * @Template()
     */
    public function indexAction()
    {
        $form = $this->createFormBuilder()
            ->add('name', null,array(
                'label' => 'Tên liên hệ',
                'widget_form_group_attr'=>array(
                    'class' => 'form-group name-group'
                )
            ))
            ->add('phone', null,array(
                'label' => 'Số điên thoại',
                'widget_form_group_attr'=>array(
                    'class' => 'form-group phone-group'
                )
            ))
            ->add('email', null,array(
                'widget_form_group_attr'=>array(
                    'class' => 'form-group email-group'
                )
            ))
            ->add('body', 'textarea',array(
                'label' => 'Nội dung',
                'widget_form_group_attr'=>array(
                    'class' => 'form-group body-group'
                )
            ))
            ->add('captcha', 'captcha', array(
                'label'=> 'Mã xác nhận',
                'width' => 150,
                'height' => 40,
                'length' => 6,
                'widget_form_group_attr'=>array(
                    'class' => 'form-group captcha-group'
                )
            ))
            ->add('gửi','submit',array(
                'attr' => array(
                    'class' => ' cusmo-btn'
                )
            ))
            ->getForm();

        if($this->getRequest()->getMethod() == 'POST'){
            $form->handleRequest($this->getRequest());
            if($form->isValid()){
                $formData = $form->getData();
                $message = \Swift_Message::newInstance()
                    ->setSubject('Virgos Beauty: Lien he tu '.$formData['name'])
                    ->setFrom('no-reply@virgosbeauty.com')
                    ->setTo('contact@virgosbeauty.com')
                    ->setBody(
                        $this->renderView(
                        'VBWebBundle:Contact:email.txt.twig',
                        array('data' => $formData)
                        )
                    )
                ;
                $this->get('mailer')->send($message);
                $this->get('session')->getFlashBag()->add('success','Gửi thành công');
                return new RedirectResponse($this->generateUrl('contact_us'));
            }
        }
        return array('form'=>$form->createView());
    }
}