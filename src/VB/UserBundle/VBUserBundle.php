<?php

namespace VB\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class VBUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }

}
