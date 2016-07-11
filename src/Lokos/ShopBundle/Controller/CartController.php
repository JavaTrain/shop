<?php

namespace Lokos\ShopBundle\Controller;

use Lokos\ShopBundle\Entity\Product;
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
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $session = $request->getSession();
        $cart    = $session->get('cart', array());
        $data    = $this->getListData(
            $request,
            'LokosShopBundle:Product',
            array('products' => array_keys($cart)),
            'id'
        );
        $data['cart'] = $cart;
        
        return $this->render('cart/index.html.twig', $data);
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    public function addToCartAction(Request $request)
    {
        $data = array();
        $id   = (int)abs($request->get('itemId', 0));
        if ($id) {
            $session   = $request->getSession();
            $cart      = $session->get('cart', array());
            if (!empty($cart[$id]) && $request->get('addButton', false)) {
                $cart[$id] = ($cart[$id] + 1);
            } else {
                $cart[$id] = (int)abs($request->get('itemCount', 1));
            }
            $session->set('cart', $cart);
        }
        if (!empty($cart)) {
            $data = $this->getDoctrine()->getRepository('LokosShopBundle:Product')->getCartCountAndPrice($cart);
        }

        return $this->jsonResponse($data);
    }

}