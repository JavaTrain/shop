<?php

namespace Lokos\ShopBundle\Controller;

use Lokos\ShopBundle\Repositories\CartRepository;
use Lokos\ShopBundle\Repositories\CategoryRepository;
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
        /** @var CartRepository $cartRepository */
        /** @var CategoryRepository $categoryRepository */
        /** @var ProductRepository $productRepository */
        $cartRepository     = $this->get('lokos.shop.cart_repository');
        $categoryRepository = $this->getDoctrine()->getRepository('LokosShopBundle:Category');
        $productRepository  = $this->getDoctrine()->getRepository('LokosShopBundle:Product');
        $filterBrand        = $request->get('brand', array());
        $filterAttribute    = $request->get('attribute', array());
        $filterOption       = $request->get('option', array());

        if (!empty($filterAttribute) || !empty($filterOption)) {
            if (empty($filterAttribute) && !empty($filterOption)) {
                $productIds = $productRepository->getIdsByFilterOptions($id, $filterBrand, $filterOption);
                $params     = array('productIds' => $productIds);
            } elseif (empty($filterOption) && !empty($filterAttribute)) {
                $productIds = $productRepository->getIdsByFilterAttributes($id, $filterBrand, $filterAttribute);
                $params     = array('productIds' => $productIds);
            } else {
                $productIds = array_intersect(
                    $productRepository->getIdsByFilterAttributes($id, $filterBrand, $filterAttribute),
                    $productRepository->getIdsByFilterOptions($id, $filterBrand, $filterOption)
                );
                $params     = $productIds?array('productIds' => $productIds):array('productIds' => -1);
            }
        } else {
            $params = array(
                'categoryId'  => $id,
                'filterBrand' => $filterBrand
            );
        }

        $data = $this->getListData(
            $request,
            'LokosShopBundle:Product',
            $params,
            'id',
            'desc'
            );

        $cartItems               = $cartRepository->getCartItems($request->getSession()->get('cart', array()));
        $data['categories']      = $categoryRepository->findAll();
        $data['cartResume']      = $cartRepository->getCartItemsCountAndPrice($cartItems);
        $data['itemCategory']    = $categoryRepository->reset()->buildQuery(['id' => $id])->getSingle();
        $data['filterBrand']     = $filterBrand;
        $data['filterAttribute'] = $filterAttribute;
        $data['filterOption']    = $filterOption;

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
        /** @var ProductRepository $productRepository */
        /** @var CartRepository $cartRepository */
        /** @var CategoryRepository $categoryRepository */
        $productRepository  = $this->getDoctrine()->getRepository('LokosShopBundle:Product');
        $cartRepository     = $this->get('lokos.shop.cart_repository');//repository without entity
        $categoryRepository = $this->getDoctrine()->getRepository('LokosShopBundle:Category');


        $item = $productRepository
                     ->reset()
                     ->buildQuery(array('productId' => $itemId))
                     ->getSingle();
        if (!$item) {
            throw new NotFoundHttpException('Item: "'.$itemId.'" not found');
        }
//        var_dump($request->getSession()->get('cart', array()));die;
//        var_dump($request->getSession()->set('cart', array()));die;
        $cartItems = $cartRepository->getCartItems($request->getSession()->get('cart', array()));

        $data = array(
            'item'             => $item,
            'options'          => $productRepository->getProductOptions($item),
            'cartResume'       => $cartRepository->getCartItemsCountAndPrice($cartItems),
            'categories'       => $categoryRepository->findAll(),
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