<?php

namespace VB\WebBundle\Service;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Routing\RouterInterface;

use Presta\SitemapBundle\Service\SitemapListenerInterface;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;

class SitemapService implements SitemapListenerInterface
{
    private $router;
    private $doctrine;

    public function __construct(RouterInterface $router, $doctrine)
    {
        $this->router = $router;
        $this->doctrine = $doctrine;
    }

    public function populateSitemap(SitemapPopulateEvent $event)
    {
        $section = $event->getSection();
        if (is_null($section) || $section == 'default') {

            // add products
            /** @var QueryBuilder $qb */
            $qb = $this->doctrine->getManager()->createQueryBuilder();
            $qb->select('p')
                ->from('VBCommerceBundle:Product','p')
                ->where($qb->expr()->eq('p.visible',true))
                ->join('p.categories','c')
                ->leftJoin('p.images','i')
                ->orderBy('p.id','desc');
            $products = $qb->getQuery()->getResult();

            foreach($products as $product){
                $url = $this->router->generate('product_by_slug', array('slug' => $product->getSlug()), true);

                $event->getGenerator()->addUrl(
                    new UrlConcrete(
                        $url,
                        new \DateTime(),
                        UrlConcrete::CHANGEFREQ_HOURLY,
                        1
                    ),
                    'default'
                );
            }

            // add articles
            $qb = $this->doctrine->getManager()->createQueryBuilder();
            $qb->select('b')
                ->from('VBCMSBundle:Blog','b')
                ->where('b.hidden = false')
                ->orderBy('b.created','desc');
            $products = $qb->getQuery()->getResult();

            foreach($articles as $article){
                $url = $this->router->generate('blog_show', array('slug' => $article->getSlug()), true);

                $event->getGenerator()->addUrl(
                    new UrlConcrete(
                        $url,
                        new \DateTime(),
                        UrlConcrete::CHANGEFREQ_HOURLY,
                        0.9
                    ),
                    'default'
                );
            }
        }
    }
}