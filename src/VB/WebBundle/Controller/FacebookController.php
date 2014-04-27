<?php

namespace VB\WebBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class FacebookController extends Controller
{
    /**
     * @return Response
     * @Route("/facebook/access_token",name="facebook_access_token", options={"expose"=true})
     */
    public function accessTokenAction()
    {
        $buzz = $this->get('buzz');
        $result = $buzz->get('https://graph.facebook.com/oauth/access_token?type=client_cred&client_id=1499107470317043&client_secret=c92a5910c4d06630a65ff3f3c0e9701b');
        $parts = explode('=',$result->getContent());

        return new Response($parts[1]);
    }
}
