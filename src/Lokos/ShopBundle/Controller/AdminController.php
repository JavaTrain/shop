<?php

namespace Lokos\ShopBundle\Controller;

use Lokos\ShopBundle\Entity\Category;
use Lokos\ShopBundle\Entity\Option;
use Lokos\ShopBundle\Entity\Product;
use Lokos\ShopBundle\Form\Type\CategoryFormType;
use Lokos\ShopBundle\Form\Type\OptionFormType;
use Lokos\ShopBundle\Form\Type\ProductFormType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Created by PhpStorm.
 * User: lamp
 * Date: 17.04.16
 * Time: 23:34
 */

class AdminController extends BaseController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('LokosShopBundle::admin.layout.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function categoriesAction()
    {
        $categories = $this->getDoctrine()
             ->getRepository('LokosShopBundle:Category')
             ->findAll();
        
        return $this->render('LokosShopBundle:Category:list.html.twig', ['categories' => $categories]);
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editCategoriesAction(Request $request)
    {
        $id      = $request->get('id', null);
        $category = $this->getDoctrine()
                        ->getRepository('LokosShopBundle:Category')
                        ->find($id);

        if (empty($category)) {
            $category = new Category();
            $title   = $this->translate('category.add_new_title');
        } else {
            $title = $this->translate('category.edit_title', array(':category' => $category->getName()));
        }

        $form = $this->createForm(CategoryFormType::class, $category);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

//            if ($request->isXmlHttpRequest()) {
//                return $this->jsonResponse($this->getErrorMessages($form));
//            }

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();

                $em->persist($category);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', $this->translate('messages.successfully_saved'));

                return $this->redirect($this->generateUrl('lokos_shop_admin_categories'));
            }
        }

        return $this->render(
            'LokosShopBundle:Category:form.html.twig',
            array(
                'form'  => $form->createView(),
                'title' => $title,
            )
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function optionsAction()
    {
        $options = $this->getDoctrine()
                        ->getRepository('LokosShopBundle:Option')
                        ->findAll();

        return $this->render('LokosShopBundle:Option:list.html.twig', ['options' => $options]);
    }

    public function editOptionAction(Request $request)
    {
        $id     = $request->get('id', null);
        $option = $this->getDoctrine()
                       ->getRepository('LokosShopBundle:Option')
                       ->find($id);

        if (empty($option)) {
            $option = new Option();
            $title  = $this->translate('option.add_new_title');
        } else {
            $title = $this->translate('option.edit_title', array(':category' => $option->getName()));
        }

        $form = $this->createForm(OptionFormType::class, $option);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            //            if ($request->isXmlHttpRequest()) {
            //                return $this->jsonResponse($this->getErrorMessages($form));
            //            }

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();

                $em->persist($option);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', $this->translate('messages.successfully_saved'));

                return $this->redirect($this->generateUrl('lokos_shop_admin_options'));
            }
        }

        return $this->render(
            'LokosShopBundle:Option:form.html.twig',
            array(
                'form'  => $form->createView(),
                'title' => $title,
            )
        );
    }

    public function productsAction()
    {
        $products = $this->getDoctrine()
                         ->getRepository('LokosShopBundle:Product')
                         ->findAll();

        return $this->render('LokosShopBundle:Product:list.html.twig', ['products' => $products]);
    }


    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editProductAction(Request $request)
    {
        $id      = $request->get('id', null);
        $product = $this->getDoctrine()
                       ->getRepository('LokosShopBundle:Product')
                       ->find($id);

        if (empty($product)) {
            $product = new Product();
            $title   = $this->translate('option.add_new_title');
        } else {
            $title = $this->translate('product.edit_title', array(':product' => $product->getName()));
        }

//        var_dump($product->getProductSets()[0]->getProduct2Options()[0]->getOption()->getName());die;
        $form = $this->createForm(ProductFormType::class, $product);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
//            var_dump($request->request->all()['lokos_shop_product']['productSets']);die;

            //            if ($request->isXmlHttpRequest()) {
            //                return $this->jsonResponse($this->getErrorMessages($form));
            //            }

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
//                var_dump(count($product->getProductSets()));die;
                foreach ($product->getProductSets() as $productSet){
                    $productSet->setProduct($product);
                    foreach ($productSet->getProduct2Options() as $product2Option){
                        $product2Option->setProduct($product);
                        $product2Option->setProductSet($productSet);
                    }
                }

                $em->persist($product);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', $this->translate('messages.successfully_saved'));

                return $this->redirect($this->generateUrl('lokos_shop_admin_products'));
            }else{
                $errors = $form->getErrors();
                foreach ($errors as $e){
                    var_dump($e->getMessage());
                }
                die;
            }
        }


        return $this->render(
            'LokosShopBundle:Product:form.html.twig',
            array(
                'form'  => $form->createView(),
                'title' => $title,
            )
        );
    }
}