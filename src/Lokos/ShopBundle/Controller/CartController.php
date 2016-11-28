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
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $cartItems = $this->get('lokos.shop.cart_repository')
                          ->getCartItems($request->getSession()->get('cart', array()));
        $data = array(
            'cartItems'  => $cartItems,
            'cartResume' => $this->get('lokos.shop.cart_repository')->getCartItemsCountAndPrice($cartItems)
        );
        
        return $this->render('Cart/index.html.twig', $data);
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    public function editAction(Request $request)
    {
        $item    = $request->get('item', null);
        $cartId  = (int)$request->get('cartId', null);
        $action  = $request->get('action');
        $session = $request->getSession();
        $cart    = $session->get('cart', array());
        if ($cartId >= 0 && $action == 'delete'){
            unset($cart[$cartId]);
            $session->set('cart', $cart);
            $msg = 'Ok';
        } elseif ($item && $cartId >= 0) {
            $itemObj = json_decode($item);
            $itemObj = $this->clearCartItem($itemObj);
            $cart[$cartId] = $itemObj;
            $session->set('cart', $cart);
            $msg = 'Ok';
        } else {
            $msg = 'Err';
        }

        return $this->jsonResponse($msg);
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    public function addToCartAction(Request $request)
    {
        $data   = array();
        $item   = $request->get('item', null);
        if ($item) {
            $itemObj = json_decode($item);
            $session = $request->getSession();
            $cart    = $session->get('cart', array());
            array_push($cart, $this->clearCartItem($itemObj));
            $session->set('cart', $cart);
            $cartItems = $this->get('lokos.shop.cart_repository')
                              ->getCartItems($cart);
            $data      = $this->get('lokos.shop.cart_repository')
                              ->getCartItemsCountAndPrice($cartItems);
        }

        return $this->jsonResponse($data);
    }

    /**
     * @param $item
     *
     * @return \stdClass
     */
    private function clearCartItem($item)
    {
        $result           = new \stdClass();
        $result->id       = (int)abs($item->id);
        $result->quantity = empty($item->quantity)?1:(int)abs($item->quantity);
        if (!empty($item->options)) {
            $options = new \stdClass();
            foreach ($item->options as $k => $v) {
                $k = (int)abs($k);
                $v = (int)abs($v);
                $options->{$k} = $v;
            }
            $result->options = $options;
        }
        
        return $result;
    }

}