<?php

namespace Lokos\ShopBundle\Controller;

use Lokos\ShopBundle\Entity\Product;
use Lokos\ShopBundle\Repositories\ProductRepository;
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
        $cart = $request->getSession()->get('cart', array());

        $productIds = [];
        foreach ($cart as $key => $item){
            $productIds[] = $item->id;
        }

        /** @var ProductRepository $productRepository */
        $productRepository = $this->getDoctrine()->getRepository('LokosShopBundle:Product');
        $products = $productRepository->reset()
                                      ->buildQuery(['productIds' => array_unique($productIds)])
                                      ->getList();

        $productData = [];
        /** @var Product $product */
        foreach ($products as $product){
            $productData[$product->getId()] = array(
                'product'          => $product,
                'availableOptions' => json_encode($productRepository->getAvailableOptions($product)),
                'options'          => $productRepository->getProductOptions($product),
            );
        }

        $data = array(
            'cart'        => $cart,
            'productData' => $productData,
        );
        
        return $this->render('LokosShopBundle:Cart:index.html.twig', $data);
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
        $data = array();
        $item = $request->get('item', null);
        if (!empty($item)) {
            $itemObj = json_decode($item);
            $session = $request->getSession();
            $cart    = $session->get('cart', array());
            $itemObj = $this->clearCartItem($itemObj);
            if(!empty($cart)){
//                var_dump($cart, $itemObj);die;
                $added = false;
                foreach ($cart as $value) {
                    if (($value->id == $itemObj->id) && ($value->productSet == $itemObj->productSet)) {
                        $value->quantity += $itemObj->quantity;
                        $added = true;
                        break;
                    }
                }
                if(!$added){
                    array_push($cart, $itemObj);
                }
            } else {
                array_push($cart, $itemObj);
            }
            $session->set('cart', $cart);
//            var_dump($cart);die;
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
        $result             = new \stdClass();
        $result->id         = (int)abs($item->id);
        $result->quantity   = empty((int)abs($item->quantity))?1:(int)abs($item->quantity);
        $result->productSet = (int)abs($item->productSet);
        
        return $result;
    }

}