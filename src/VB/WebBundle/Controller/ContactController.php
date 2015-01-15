<?php

namespace VB\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ContactController extends Controller
{
    /**
     * @Route("/contact-us",name="contact_us", options={"sitemap" = {"priority" = 0.7, "changefreq" = "weekly"}})
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
        if($this->getUser()){
            $form->get('email')->setData($this->getUser()->getEmail());
            $form->get('phone')->setData($this->getUser()->getPhone());
            $form->get('name')->setData($this->getUser()->getDisplayName());
        }

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

        $seoPage = $this->container->get('sonata.seo.page');
        $title = 'Liên hệ chúng tôi';
        $url = $this->generateUrl('contact_us',array(),true);
        $seoPage
            ->setTitle($title)
            ->addMeta('property', 'og:title',$title )
            ->addMeta('property', 'og:url',  $url)
        ;
        $seoPage->setLinkCanonical($url);

        return array('form'=>$form->createView());
    }
}
