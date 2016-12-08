<?php

namespace Lokos\ShopBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
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

        return $this->render('LokosShopBundle:Product:overview.html.twig', $data);

    }

    /**
     * @param Request $request
     * @param         $catId
     * @param         $itemId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detailAction(Request $request, $catId, $itemId, $options=null)
    {
        $params = array('withOptions' => $itemId);
        if ($request->isXmlHttpRequest()) {
        }

        $item = $this->getDoctrine()
                     ->getRepository('LokosShopBundle:Product')
                     ->reset()
                     ->buildQuery($params)
                     ->getSingle();
//        $withOptions = true;
        if (!$item) {
            throw new NotFoundHttpException('Item "'.$itemId.'" not found');
        }
//        var_dump($request->getSession()->get('cart', array()));die;
//        var_dump($request->getSession()->set('cart', array()));die;
        $cartItems = $this->get('lokos.shop.cart_repository')
                          ->getCartItems($request->getSession()->get('cart', array()));

//        var_dump($item->getProductSets()->toArray());die;

        $data = array(
            'item'             => $item,
            'options'          => $this->getDoctrine()->getRepository('LokosShopBundle:Product')->getProductOptions($item),
            'cartResume'       => $this->get('lokos.shop.cart_repository')->getCartItemsCountAndPrice($cartItems),
            'categories'       => $this->getDoctrine()->getRepository('LokosShopBundle:Category')->findAll(),
            'availableOptions' => json_encode($this->getDoctrine()->getRepository('LokosShopBundle:Product')->getAvailableOptions($item)),
//            'withOptions'      => $withOptions,
        );

        if ($request->isMethod('POST')) {
            $response = $this->render('LokosShopBundle:Product:detail_block.html.twig', $data);
        } else {
            $response = $this->render('LokosShopBundle:Product:detail.html.twig', $data);
        }

        return $response;
    }

//    private function getAvailableOptions(Product $item)
//    {
//        $options = [];
//        foreach($item->getProductSets() as $ps){
//            foreach ($ps->getProduct2Options() as $po) {
//                $options[$ps->getId()][] = [
//                    'optionId'   => $po->getOption()->getId(),
//                    'valueId'    => $po->getOptionValue()->getId(),
//                ];
//            }
//        }
//
////        var_dump($options);die;
//
//        return $options;
//    }

//    /**
//     * @param Product $item
//     *
//     * @return array|null
//     */
//    private function getProductOptions(Product $item)
//    {
//        if(!$item->getProductSets()){
//            return null;
//        }
//
//        $options      = [];
//        $optionFilter = [];
//        $values       = [];
//        $valueFilter  = [];
//        foreach ($item->getProductSets() as $ps) {
//            $ps->getProduct2Options()->filter(
//                function ($entry) use (&$optionFilter, &$options, &$values, &$valueFilter) {
//                    if (!array_key_exists($entry->getOption()->getId(), $optionFilter)) {
//                        $options[$entry->getOption()->getId()]                         = $entry->getOption();
//                        $optionFilter[$entry->getOption()->getId()] = $entry->getOption()->getId();
//                        if(!array_key_exists($entry->getOptionValue()->getId(), $valueFilter)){
//                            $valueFilter[$entry->getOptionValue()->getId()] = $entry->getOptionValue()->getId();
//                            $values[$entry->getOption()->getId()][] = $entry->getOptionValue();
//                        }
//                        return true;
//                    } else {
//                        if(!array_key_exists($entry->getOptionValue()->getId(), $valueFilter)){
//                            $valueFilter[$entry->getOptionValue()->getId()] = $entry->getOptionValue()->getId();
//                            $values[$entry->getOption()->getId()][] = $entry->getOptionValue();
//                        }
//                        return false;
//                    }
//                }
//            );
//        }
//
//        return [$options, $values];
//    }

}