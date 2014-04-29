<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),

            new FOS\UserBundle\FOSUserBundle(),
            new Mopa\Bundle\BootstrapBundle\MopaBootstrapBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new WhiteOctober\BreadcrumbsBundle\WhiteOctoberBreadcrumbsBundle(),
            new Knp\Bundle\DisqusBundle\KnpDisqusBundle(),
            new Sensio\Bundle\BuzzBundle\SensioBuzzBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new Trsteel\CkeditorBundle\TrsteelCkeditorBundle(),
            new Exercise\HTMLPurifierBundle\ExerciseHTMLPurifierBundle(),
            new Knp\Bundle\GaufretteBundle\KnpGaufretteBundle(),
            new Vich\UploaderBundle\VichUploaderBundle(),
            new Liip\ImagineBundle\LiipImagineBundle(),
            new HWI\Bundle\OAuthBundle\HWIOAuthBundle(),

            new VB\BlogBundle\VBBlogBundle(),
            new VB\CommerceBundle\VBCommerceBundle(),
            new VB\UserBundle\VBUserBundle(),
            new VB\WebBundle\VBWebBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new RaulFraile\Bundle\LadybugBundle\RaulFraileLadybugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
