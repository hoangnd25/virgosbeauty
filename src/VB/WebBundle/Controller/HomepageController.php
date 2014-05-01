<?php

namespace VB\WebBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class HomepageController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction()
    {
        /**
         * @var $em EntityManager
         */
        $em = $this->getDoctrine()->getManager();

        $products = null;
        $qb = $em->createQueryBuilder();
        $qb->select('p')
            ->from('VBCommerceBundle:Product','p')
            ->join('p.categories','c')
            ->leftJoin('p.images','i')
            ->orderBy('p.id','desc');
        $qb->setMaxResults(8);

        return array('products' => $qb->getQuery()->getResult());
    }
}
