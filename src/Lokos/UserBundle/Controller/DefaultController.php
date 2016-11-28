<?php

namespace Lokos\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('LokosUserBundle:Default:index.html.twig');
    }
}
