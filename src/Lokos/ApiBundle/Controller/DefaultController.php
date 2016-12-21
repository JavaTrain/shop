<?php

namespace Lokos\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('LokosApiBundle:Default:index.html.twig');
    }
}
