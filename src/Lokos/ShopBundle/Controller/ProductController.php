<?php

namespace Lokos\ShopBundle\Controller;

use Lokos\ShopBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Created by PhpStorm.
 * User: lamp
 * Date: 17.04.16
 * Time: 23:34
 */

class ProductController extends BaseController
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
        $data      = array(
            'categories' => $this->getDoctrine()->getRepository('LokosShopBundle:Category')->findAll(),
            'cartResume' => $this->get('lokos.shop.cart_repository')->getCartItemsCountAndPrice($cartItems),
        );

        return $this->render('Product/index.html.twig', $data);
    }

    /**
     * @param Request $request
     * @param         $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function overviewAction(Request $request, $id)
    {
        $data = $this->getListData(
            $request,
            'LokosShopBundle:Product',
            array('categoryId' => $id),
            'id',
            'desc'
            );

        $data['categories'] = $this->getDoctrine()
                                   ->getRepository('LokosShopBundle:Category')
                                   ->findAll();
        $cartItems          = $this->get('lokos.shop.cart_repository')
                                   ->getCartItems($request->getSession()->get('cart', array()));
        $data['cartResume'] = $this->get('lokos.shop.cart_repository')
                                   ->getCartItemsCountAndPrice($cartItems);
        $data['catId'] = $id;

        return $this->render('Product/overview.html.twig', $data);
    }

    /**
     * @param Request $request
     * @param         $catId
     * @param         $itemId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detailAction(Request $request, $catId, $itemId)
    {
        $item = $this->getDoctrine()
                     ->getRepository('LokosShopBundle:Product')
                     ->reset()
                     ->buildQuery(array('withOptions' => $itemId))
                     ->getSingle();
        $withOptions = true;
        if (!$item) {
            $item = $this->getDoctrine()
                         ->getRepository('LokosShopBundle:Product')
                         ->reset()
                         ->buildQuery(array('id' => $itemId))
                         ->getSingle();
            $withOptions = false;
        }
        if (!$item) {
            throw new NotFoundHttpException('Item "'.$itemId.'" not found');
        }

        $cartItems = $this->get('lokos.shop.cart_repository')
                          ->getCartItems($request->getSession()->get('cart', array()));

        $data = array(
            'item'        => $item,
            'cartResume'  => $this->get('lokos.shop.cart_repository')->getCartItemsCountAndPrice($cartItems),
            'categories'  => $this->getDoctrine()->getRepository('LokosShopBundle:Category')->findAll(),
            'withOptions' => $withOptions,
        );

        if ($request->isMethod('POST')) {
            $response = $this->render('Product/detail_block.html.twig', $data);
        } else {
            $response = $this->render('Product/detail.html.twig', $data);
        }

        return $response;
    }

}