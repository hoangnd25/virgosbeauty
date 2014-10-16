<?php

namespace VB\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LandingPageController extends Controller
{
    /**
     * @Route("/km-cham-soc-da-nhau-thai-cuu",name="promotion_placenta_product")
     * @Template()
     */
    public function placentaProductAction()
    {
        $em =  $this->getDoctrine()->getManager();
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Home", $this->get("router")->generate("homepage"));
        $breadcrumbs->addItem("Khuyến mãi các sản phẩm chăm sóc da từ nhau thai cừu");

        $qb = $em->createQueryBuilder();
        $qb->select('p');
        $qb->from('VBCommerceBundle:Product', 'p');
        $qb->where($qb->expr()->in('p.id', array(1, 3,4)));
        $lanolin = $qb->getQuery()->getResult();

        $qb = $em->createQueryBuilder();
        $qb->select('p');
        $qb->from('VBCommerceBundle:Product', 'p');
        $qb->where($qb->expr()->in('p.id', array(23, 24,25)));
        $rebirth = $qb->getQuery()->getResult();

        return array('lanolin'=>$lanolin,'rebirth'=>$rebirth);
    }
}
