<?php

namespace Lokos\ShopBundle\Controller;

use Lokos\ShopBundle\Repositories\CartRepository;
use Lokos\ShopBundle\Repositories\ProductRepository;
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

        return $this->render('LokosShopBundle:Product:index.html.twig', $data);
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
                                   ->getRepository('LokosShopBundle:Category')->findAll();
        $cartItems          = $this->get('lokos.shop.cart_repository')
                                   ->getCartItems($request->getSession()->get('cart', array()));
        $data['cartResume'] = $this->get('lokos.shop.cart_repository')
                                   ->getCartItemsCountAndPrice($cartItems);
        $data['catId']      = $id;

        return $this->render('LokosShopBundle:Product:overview.html.twig', $data);

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
//        if ($request->isXmlHttpRequest()) {
//        }
        /** @var ProductRepository $productRepository */
        /** @var CartRepository $cartRepository */
        $productRepository = $this->getDoctrine()->getRepository('LokosShopBundle:Product');
        $cartRepository    = $this->get('lokos.shop.cart_repository');//repository without entity

        $item = $productRepository
                     ->reset()
                     ->buildQuery(array('productId' => $itemId))
                     ->getSingle();
        if (!$item) {
            throw new NotFoundHttpException('Item "'.$itemId.'" not found');
        }
//        var_dump($request->getSession()->get('cart', array()));die;
//        var_dump($request->getSession()->set('cart', array()));die;
        $cartItems = $cartRepository->getCartItems($request->getSession()->get('cart', array()));

        $data = array(
            'item'             => $item,
            'options'          => $productRepository->getProductOptions($item),
            'cartResume'       => $cartRepository->getCartItemsCountAndPrice($cartItems),
            'categories'       => $this->getDoctrine()->getRepository('LokosShopBundle:Category')->findAll(),
            'availableOptions' => json_encode($productRepository->getAvailableOptions($item)),
        );

        if ($request->isMethod('POST')) {
            $response = $this->render('LokosShopBundle:Product:detail_block.html.twig', $data);
        } else {
            $response = $this->render('LokosShopBundle:Product:detail.html.twig', $data);
        }

        return $response;
    }

}