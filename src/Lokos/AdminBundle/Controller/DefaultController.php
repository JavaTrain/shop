<?php

namespace Lokos\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('LokosAdminBundle:Default:index.html.twig');
    }
}
