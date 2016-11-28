<?php

namespace Lokos\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Lokos\ShopBundle\Controller\BaseController;

/**
 * Created by PhpStorm.
 * User: lamp
 * Date: 17.04.16
 * Time: 23:34
 */

class CategoryController extends BaseController
{
    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $categories = $this->getDoctrine()->getRepository('LokosShopBundle:Category')->findAll();
        
        $data = array(
            'categories'  => $categories,
        );
        
        return $this->render('LokosAdminBundle:Category:index.html.twig', $data);
    }

    
}