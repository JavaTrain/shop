<?php

namespace Lokos\ShopBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 * Created by PhpStorm.
 * User: lamp
 * Date: 17.04.16
 * Time: 23:34
 */

class CartController extends BaseController
{

    /**
     * @param Request $request
     * @param         $id
     *
     * @return string
     */
    public function addToCartAction(Request $request, $id)
    {
        $session   = $request->getSession();
        $cart      = $session->get('cart', array());
        $cart[$id] = $request->get('count', 1);
        $session->set('cart', $cart);

        return $this->jsonResponse('Ok');
    }

}