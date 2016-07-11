<?php

namespace Lokos\ShopBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

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
        $cart      = $request->getSession()->get('cart', array());
        $data = array(
            'categories' => $this->getDoctrine()->getRepository('LokosShopBundle:Category')->findAll(),
        );
        if ($cart) {
            $data['cart'] = $this->getDoctrine()->getRepository('LokosShopBundle:Product')->getCartCountAndPrice($cart);
        } else {
            $data['cart'] = array('sum' => '0', 'price' => '0.00');
        }

        return $this->render('product/index.html.twig', $data);
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

        $data['categories'] = $this->getDoctrine()->getRepository('LokosShopBundle:Category')->findAll();

        return $this->render('product/overview.html.twig', $data);
    }

}