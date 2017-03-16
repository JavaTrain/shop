<?php

namespace Lokos\ShopBundle\Controller;

use Lokos\ShopBundle\Entity\Attribute;
use Lokos\ShopBundle\Entity\AttributeValue;
use Lokos\ShopBundle\Entity\Brand;
use Lokos\ShopBundle\Entity\Category;
use Lokos\ShopBundle\Entity\Option;
use Lokos\ShopBundle\Entity\OptionValue;
use Lokos\ShopBundle\Entity\Product;
use Lokos\ShopBundle\Entity\Product2Attribute;
use Lokos\ShopBundle\Entity\Product2Option;
use Lokos\ShopBundle\Entity\ProductSet;
use Lokos\ShopBundle\Form\Type\AttributeFormType;
use Lokos\ShopBundle\Form\Type\BrandFormType;
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
        $category = $this->getDoctrine()
                         ->getRepository('LokosShopBundle:Category')
                         ->find($request->get('id', null));

        if (empty($category)) {
            $category = new Category();
            $title    = $this->translate('category.add_new_title');
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
        $categories = $this->getDoctrine()
                           ->getRepository('LokosShopBundle:Category')
                           ->findAll(array(), array('name' => 'ASC'));

        return $this->render('LokosShopBundle:Option:list.html.twig', ['categories' => $categories]);
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
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
            $title = $this->translate('option.edit_title', array(':option' => $option->getName()));
        }

        $form = $this->createForm(OptionFormType::class, $option);

        if ($request->isMethod('POST')) {
            $beforeSaveOptionValues = $currentOptionValuesIds = array();
            foreach ($option->getOptionValues() as $optionValue)
                $beforeSaveOptionValues [$optionValue->getId()] = $optionValue;

            $form->handleRequest($request);

//            if ($request->isXmlHttpRequest()) {
//                die('frfrffr');
//                return $this->jsonResponse($this->getErrorMessages($form));
//            }

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($option);
                /** @var OptionValue $optionValue */
                foreach ($option->getOptionValues() as $optionValue) {
                    $optionValue->setOption($option);
                    if ($optionValue->getId()) {
                        $currentOptionValuesIds[] = $optionValue->getId();
                    }
                }
                $em->persist($option);
                foreach ($beforeSaveOptionValues as $optionValueId => $optionValue) {
                    if (!in_array($optionValueId, $currentOptionValuesIds)) {
                        $em->remove($optionValue);
                    }
                }

                $em->flush();

                $this->get('session')->getFlashBag()->add('success', $this->translate('messages.successfully_saved'));

                return $this->redirect($this->generateUrl('lokos_shop_admin_options'));
            } else {
                foreach ($form->getErrors() as $e) {
                    var_dump($e->getMessage());
                    die;
                }
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

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
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
        /** @var Product $product */
        $product = $this->getDoctrine()
                        ->getRepository('LokosShopBundle:Product')
                        ->buildQuery(array('productId' => $request->get('id', null)))
                        ->getSingle();

        if (empty($product)) {
            $product = new Product();
            $title   = $this->translate('option.add_new_title');
        } else {
            $title = $this->translate('product.edit_title', array(':product' => $product->getName()));
        }

//        var_dump($product->getProductSets());die;
        $form = $this->createForm(ProductFormType::class, $product);
//        var_dump($product);die('454353');

        if ($request->isMethod('POST')) {
            $beforeSaveProductSets = $currentProductSetIds = array();
            $beforeSaveAttributes = $currentProductAttributes = array();
            /** @var ProductSet $productSet */
            foreach ($product->getProductSets() as $productSet){
                $beforeSaveProductSets[$productSet->getId()] = $productSet;
            }
            /** @var Product2Attribute $p2a */
            foreach ($product->getProduct2Attributes() as $p2a){
                $beforeSaveAttributes[$p2a->getId()] = $p2a;
            }

            $form->handleRequest($request);

            if ($request->isXmlHttpRequest()) {
                if (!$request->get('update')) {
                    return $this->jsonResponse($this->getErrorMessages($form));
                }
            }

            if (!$request->get('update')) {

                if ($form->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    foreach ($product->getProductSets() as $productSet) {
                        $productSet->setProduct($product);
                        if ($productSet->getId()) {
                            $currentProductSetIds[] = $productSet->getId();
                        }
                        /** @var Product2Option $product2Option */
                        foreach ($productSet->getProduct2Options() as $product2Option) {
                            $product2Option->setProductSet($productSet);
                        }
                    }
                    foreach ($beforeSaveProductSets as $productSetId => $productSet) {
                        if (!in_array($productSetId, $currentProductSetIds)) {
                            $em->remove($productSet);
                        }
                    }
                    foreach ($product->getProduct2Attributes() as $p2a) {
                        $p2a->setProduct($product);
                        if ($p2a->getId()) {
                            $currentProductAttributes[] = $p2a->getId();
                        }
                    }
                    foreach ($beforeSaveAttributes as $productAttrId => $productAttr) {
                        if (!in_array($productAttrId, $currentProductAttributes)) {
                            $em->remove($productAttr);
                        }
                    }

                    $em->persist($product);
                    $em->flush();

                    $this->get('session')->getFlashBag()->add('success', $this->translate('messages.successfully_saved'));

                    return $this->redirect($this->generateUrl('lokos_shop_admin_products'));
                } else {

                    $errors = $form->getErrors();
                    foreach ($errors as $e) {
                        var_dump($e->getMessage());
                    }die;
                }
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

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function brandsAction()
    {
        $categories = $this->getDoctrine()
                           ->getRepository('LokosShopBundle:Category')
                           ->findAll(array(), array('name' => 'ASC'));

        return $this->render('LokosShopBundle:Brand:list.html.twig', ['categories' => $categories]);
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editBrandAction(Request $request)
    {
        $brand = $this->getDoctrine()
                      ->getRepository('LokosShopBundle:Brand')
                      ->find($request->get('id', null));

        if (empty($brand)) {
            $brand = new Brand();
            $title  = $this->translate('brand.add_new_title');
        } else {
            $title = $this->translate('brand.edit_title', array(':brand' => $brand->getName()));
        }

        $form = $this->createForm(BrandFormType::class, $brand);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();

                $sql = "DELETE FROM `brand2category` WHERE `brand_id` = :brand_id";
                $em->getConnection()->executeQuery($sql, [':brand_id' => $brand->getId()]);

                /** @var Category $category */
                foreach ($brand->getCategories() as $category) {
                    $brand->addCategories($category);
                    $category->addBrands($brand);
//                    $em->persist($category);
                }
                $em->persist($brand);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', $this->translate('messages.successfully_saved'));

                return $this->redirect($this->generateUrl('lokos_shop_admin_brands'));
            } else {
                foreach ($form->getErrors() as $e) {
                    var_dump($e->getMessage());
                    die;
                }
            }
        }

        return $this->render(
            'LokosShopBundle:Brand:form.html.twig',
            array(
                'form'  => $form->createView(),
                'title' => $title,
            )
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function attributesAction()
    {
        $categories = $this->getDoctrine()
                           ->getRepository('LokosShopBundle:Category')
                           ->findAll(array(), array('name' => 'ASC'));

        return $this->render('LokosShopBundle:Attribute:list.html.twig', ['categories' => $categories]);
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAttributeAction(Request $request)
    {
        /** @var Attribute $attribute */
        $attribute = $this->getDoctrine()
                          ->getRepository('LokosShopBundle:Attribute')
                          ->find($request->get('id', null));

        if (empty($attribute)) {
            $attribute = new Attribute();
            $title  = $this->translate('attribute.add_new_title');
        } else {
            $title = $this->translate('attribute.edit_title', array(':attribute' => $attribute->getName()));
        }

        $form = $this->createForm(AttributeFormType::class, $attribute);

        if ($request->isMethod('POST')) {
            $beforeSaveAttributeValues = $currentAttributeValuesIds = array();
            /** @var AttributeValue $attributeValue */
            foreach ($attribute->getAttributeValues() as $attributeValue)
                $beforeSaveAttributeValues [$attributeValue->getId()] = $attributeValue;

            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();

                foreach ($attribute->getAttributeValues() as $attributeValue) {
                    $attributeValue->setAttribute($attribute);
                    if ($attributeValue->getId()) {
                        $currentAttributeValuesIds[] = $attributeValue->getId();
                    }
                }

                $em->persist($attribute);

                foreach ($beforeSaveAttributeValues as $attributeValueId => $attributeValue) {
                    if (!in_array($attributeValueId, $currentAttributeValuesIds)) {
                        $em->remove($attributeValue);
                    }
                }

                $em->flush();

                $this->get('session')->getFlashBag()->add('success', $this->translate('messages.successfully_saved'));

                return $this->redirect($this->generateUrl('lokos_shop_admin_attributes'));
            } else {
                foreach ($form->getErrors() as $e) {
                    var_dump($e->getMessage());
                    die;
                }
            }
        }

        return $this->render(
            'LokosShopBundle:Attribute:form.html.twig',
            array(
                'form'  => $form->createView(),
                'title' => $title,
            )
        );
    }
}