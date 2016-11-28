<?php

namespace Lokos\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('LokosShopBundle:Default:index.html.twig');
    }
}
