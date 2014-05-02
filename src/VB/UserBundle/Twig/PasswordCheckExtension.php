<?php

namespace VB\UserBundle\Twig;

class PasswordCheckExtension extends \Twig_Extension
{
    protected $encoder;
    protected $securityContext;

    public function __construct($encoder,$securityContext){
        $this->encoder = $encoder;
        $this->securityContext = $securityContext;
    }

    public function getUser()
    {
        return $this->securityContext->getToken()->getUser();
    }

    public function getFunctions()
    {
        return array(
            'check_password' => new \Twig_Function_Method($this, 'checkPassword')
        );
    }

    public function checkPassword($password)
    {
        $encoder = $this->encoder->getEncoder($this->getUser());
        $encodedPass = $encoder->encodePassword($password, $this->getUser()->getSalt());

        return $encodedPass == $this->getUser()->getPassword();
    }

    public function getName()
    {
        return 'check_name_extension';
    }
}