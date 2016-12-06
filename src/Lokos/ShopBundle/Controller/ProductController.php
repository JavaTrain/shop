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
            //get option's values
        }

        $item = $this->getDoctrine()
                     ->getRepository('LokosShopBundle:Product')
                     ->reset()
                     ->buildQuery($params)
                     ->getSingle();
        $withOptions = true;
        if (!$item) {
            throw new NotFoundHttpException('Item "'.$itemId.'" not found');
        }
//        $request->getSession()->set('cart', array());
        $cartItems = $this->get('lokos.shop.cart_repository')
                          ->getCartItems($request->getSession()->get('cart', array()));

//        var_dump($item->getProductSets()->toArray());die;

        $data = array(
            'item'             => $item,
            'options'          => $this->getProductOptions($item),
            'cartResume'       => $this->get('lokos.shop.cart_repository')->getCartItemsCountAndPrice($cartItems),
            'categories'       => $this->getDoctrine()->getRepository('LokosShopBundle:Category')->findAll(),
            'availableOptions' => json_encode($this->getAvailableOptions($item)),
            'withOptions'      => $withOptions,
        );

        if ($request->isMethod('POST')) {
            $response = $this->render('LokosShopBundle:Product:detail_block.html.twig', $data);
        } else {
            $response = $this->render('LokosShopBundle:Product:detail.html.twig', $data);
        }

        return $response;
    }

    private function getAvailableOptions(Product $item)
    {
        $options = [];
        foreach($item->getProductSets() as $ps){
            foreach ($ps->getProduct2Options() as $po) {
                $options[$ps->getId()][] = [
                    'optionId'   => $po->getOption()->getId(),
                    'valueId'    => $po->getOptionValue()->getId(),
                ];
            }
        }

//        var_dump($options);die;

        return $options;
    }


    /**
     * @param Product $item
     *
     * @return array|null
     */
    private function getProductOptions(Product $item)
    {
        if(!$item->getProductSets()){
            return null;
        }

        $options = [];
        $arr     = [];

        foreach ($item->getProductSets() as $ps) {
            $ps->getProduct2Options()->filter(
                function ($entry) use (&$arr, &$options) {
                    if (!array_key_exists($entry->getOption()->getId(), $arr)) {
                        $options[]                         = $entry->getOption();
                        $arr[$entry->getOption()->getId()] = $entry->getOption()->getId();
                        return true;
                    } else {
                        return false;
                    }
                }
            );
        }

        $arr = $optVals = [];
        foreach($options as $opt){
            $optVals[] = $opt->getOptionValues()->filter(function($entry) use (&$arr){
                if (!array_key_exists($entry->getId(), $arr)) {
                    $arr[$entry->getId()] = $entry->getId();
                    return true;
                } else {
                    return false;
                }
            });
        }

        return [$options, $optVals];





//        var_dump($optVals);die;

//        $opts = $item->getProductSets()->filter(function($entry) use ($arr){
//            if(!array_key_exists($entry->getProduct2Options()->getId(), $arr)){
//                $arr[$entry->getProduct2Options()->getId()] = $entry->getProduct2Options()->getId();
//                return true;
//            } else{
//                return false;
//            }
//        });

//        var_dump($opts);die;


//        $options = [];
//        foreach($item->getProductSets() as $ps){
//            foreach ($ps->getProduct2Options() as $po) {
//                $options[$ps->getId()][] = [
//                    'optionId'   => $po->getOption()->getId(),
//                    'optionName' => $po->getOption()->getName(),
//                    'valueId'    => $po->getOptionValue()->getId(),
//                    'valueData'  => $po->getOptionValue()->getValue(),
//                    'prodSetId'  => $ps->getId()
//                ];
//            }
//        }
//
//        $optShow = [];
//        foreach ($options as $opts){
//            foreach ($opts as $opt){
//                $optShow[$opt[0]][] = $opt[2];
//            }
//        }
//        $result = [];
//        foreach($optShow as $k => $v){
//            $result[$k] = array_unique($v);
//        }
//
//
//
//
//        var_dump($optShow);
//        var_dump($result);
        var_dump($options);die;
    }

}