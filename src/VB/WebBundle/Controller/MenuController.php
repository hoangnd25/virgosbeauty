<?php

namespace VB\WebBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use VB\CommerceBundle\Entity\ProductCategory;

/**
 * Class MenuController
 * @package VB\WebBundle\Controller
 */
class MenuController extends Controller
{
    /**
     * @Template()
     */
    public function mainMenuAction($category=null,$route=null)
    {
        $children = $this->getRepo()->children($this->getRoot(),true);
        if(!$category){
            $category = new ProductCategory();
        }
        return array('menu'=>$children,'category'=>$category,'route'=>$route);
    }

    /**
     * @Template()
     */
    public function mobileMainMenuAction()
    {
        $children = $this->getRepo()->children($this->getRoot());
        return array('menu'=>$children);
    }

    /**
     * @Template()
     */
    public function categoryWidgetAction($category=null)
    {
        $children = $this->getRepo()->children($this->getRoot());
        if(!$category){
            $category = new ProductCategory();
        }
        return array('menu'=>$children,'category'=>$category);
    }


    protected function getRepo(){
        /**
         * @var $em EntityManager
         */
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('VBCommerceBundle:ProductCategory');
        return $repo;
    }
    protected function getRoot(){
        /**
         * @var $repo NestedTreeRepository
         */
        $root = $this->getRepo()->findOneBy(array('slug'=>'root'));

        return $root;
    }
}
