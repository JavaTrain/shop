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

    public function getAction($catId, $prodId)
    {
        return [
            'product' => $this->getDoctrine()->getRepository('LokosShopBundle:Product')->findOneBy(['id' => $prodId]),
        ];
    }

    public function cgetAction($catId)
    {
        return [
            'products' => $this->getDoctrine()->getRepository('LokosShopBundle:Product')->findBy(['category' => $catId]),
        ];
    }
}
