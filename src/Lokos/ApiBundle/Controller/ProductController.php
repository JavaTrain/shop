<?php

namespace Lokos\ApiBundle\Controller;

use FOS\RestBundle\Request\ParamFetcher;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Request\ParamFetcherInterface;

/**
 * @Rest\RouteResource("product")
 */
class ProductController extends FOSRestController
{

    /**
     * @param $catId
     * @param $prodId
     *
     * @return array
     */
    public function getAction($catId, $prodId)
    {
        return [
            'product' => $this->getDoctrine()->getRepository('LokosShopBundle:Product')->findOneBy(['id' => $prodId]),
        ];
    }

    /**
     * @param Request $request
     * @param         $catId
     *
     * @return array
     */
    public function cgetAction(Request $request, $catId)
    {
        if($request->query->has('productIds')){
            $productIds = $request->get('productIds', array());
            if (empty($productIds)) {
                $response = array();
            } else {
                $response = ['products' => $this->getDoctrine()->getRepository('LokosShopBundle:Product')->findBy(['id' => $productIds])];
            }
        } else {
            $response = ['products' => $this->getDoctrine()->getRepository('LokosShopBundle:Product')->findBy(['category' => $catId])];
        }

        return $response;
    }
}
