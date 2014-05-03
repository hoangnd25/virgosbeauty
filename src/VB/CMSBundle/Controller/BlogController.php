<?php

namespace VB\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BlogController extends Controller
{
    /**
     * @Route("/a",name="blog")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->createQueryBuilder();
        $qb->select('b')
            ->from('VBCMSBundle:Blog','b')
            ->where('b.hidden = false')
            ->orderBy('b.created','desc');

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $qb->getQuery(),
            $this->get('request')->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return array('blogs'=>$pagination);
    }

    /**
     * @Route("/a/{slug}",name="blog_show")
     * @Template()
     */
    public function showAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $blog = $em->getRepository('VBCMSBundle:Blog')->findOneBy(array('slug'=>$slug));
        if(!$blog)
            throw new NotFoundHttpException('Không tìm thấy bài viết');

        $blog->setPageViews($blog->getPageViews() + 1);
        $em->persist($blog);
        $em->flush();

        return array('blog'=>$blog);
    }
}
