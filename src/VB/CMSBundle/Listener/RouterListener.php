<?php

namespace VB\CMSBundle\Listener;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Validator\Constraints\UrlValidator;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Validator;

/**
 * Class RouterListener
 * @package Local\MiscBundle\EventListener
 * http://joboudev.wordpress.com/2013/04/21/dynamic-route-in-symfony2/
 */
class RouterListener
{
    /** @var Router $router */
    protected $router;
    protected $doctrine;
    /** @var  Validator $validator */
    protected $validator;

    public function __construct($router,$doctrine,$validator)
    {
        $this->router = $router;
        $this->doctrine = $doctrine;
        $this->validator = $validator;
    }

    /**
     * onKernelRequest called on event kernel.request
     *
     * @param GetResponseEvent $event the event dispatched on kernel.request
     * @return array|null
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernel::MASTER_REQUEST != $event->getRequestType()) {
            return;
        }
        $request = $event->getRequest();

        if ($request->attributes->has('_controller')) {
            // routing is already done
            return;
        }

        try {
            $uri = $request->getPathInfo();
            // try to remove / at the end of uri
            $uri = preg_replace('/\/$/','',$uri,1);

            /** @var EntityManager $em */
            $em = $this->doctrine->getManager();

            $redirectRule = $em->getRepository('VBCMSBundle:RedirectRule')->findOneBy(array(
                'matchingUri' => $uri
            ));

            if($redirectRule){
                $redirectUri = $redirectRule->getRedirectUri();

                $urlConstraint = new Url();
                $errors = $this->validator->validateValue($redirectUri,$urlConstraint);

                if($errors->count() != 0 ){
                    $matchedRoute = $this->router->match($redirectUri);
                    $parameters = array();

                    foreach($matchedRoute as $key => $value){
                        if($key !== '_controller' && $key!== '_route')
                            $parameters[$key] = $value;
                    }

                    $redirectUri = $this->router->generate($matchedRoute['_route'],$parameters);
                }
                $event->setResponse(new RedirectResponse($redirectUri, $redirectRule->getStatusCode()));
            }

        } catch (ResourceNotFoundException $e) {
            return;
        }
    }
}