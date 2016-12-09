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
        $session = $request->getSession();
        $cart    = $session->get('cart', array());
        if ($item ) {
            $cartObj = json_decode($item);
            $cart = [];
            foreach ($cartObj as $key => $item){
                $cart[$key] = $this->clearCartItem($item);
            }
            $session->set('cart', $cart);
            $msg = 'Ok';
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
        $cartRepository = $this->get('lokos.shop.cart_repository');
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
            $cartItems = $cartRepository->getCartItems($cart);
            $data      = $cartRepository->getCartItemsCountAndPrice($cartItems);
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
        $obj             = new \stdClass();
        $obj->id         = (int)abs($item->id);
        $obj->quantity   = empty($item->quantity)?1:abs((int)$item->quantity);
        $obj->productSet = empty($item->productSet)?0:abs((int)$item->productSet);

        return $obj;
    }

}