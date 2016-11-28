<?php

namespace Lokos\ShopBundle\Controller;

use Lokos\ShopBundle\Entity\Order;
use Lokos\ShopBundle\Entity\OrderDetail;
use Lokos\ShopBundle\Form\Type\OrderFormType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Created by PhpStorm.
 * User: lamp
 * Date: 17.04.16
 * Time: 23:34
 */

class OrderController extends BaseController
{
    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $order = new Order();
        $form = $this->createForm(OrderFormType::class, $order);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

//            if ($request->isXmlHttpRequest()) {
//                return $this->jsonResponse($this->getErrorMessages($form));
//            }

            if ($form->isValid()) {
                $cartItems = $this->get('lokos.shop.cart_repository')
                                  ->getCartItems($request->getSession()->get('cart', array()));

                $em = $this->getDoctrine()->getManager();
                foreach ($cartItems as $item) {
                    /** @var OrderDetail $orderDetail */
                    $orderDetail= new OrderDetail();
                    $orderDetail->setOrder($order);
                    $orderDetail->setQuantity($item['quantity']);
                    $orderDetail->setProduct($item['product']);
                    if (!empty($item['product']->getOptions())) {
                        foreach ($item['product']->getOptions() as $option) {
                            $orderDetail->setOptionValues($option->getOptionValues());
                        }
                    }
                    $em->persist($orderDetail);
                }
                $em->persist($order);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', $this->translate('messages.successfully_saved'));

                return $this->redirect($this->generateUrl('mindk_publish_my_contact'));
            } else {
                $errors = $form->getErrors();
                var_dump($errors);die;
            }
        }
        
        return $this->render('LokosShopBundle:Order:form.html.twig', array('form' => $form->createView()));
    }


}