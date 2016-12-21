<?php

namespace Lokos\ApiBundle\Controller;

use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Routing\ClassResourceInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Controller\Annotations\View;

/**
 * @Rest\RouteResource("category")
 */
class CategoryController extends FOSRestController
{

    public function getAction($catId)
    {
        return [
            'category' => $this->getDoctrine()->getRepository('LokosShopBundle:Category')->findOneBy(['id' => $catId]),
        ];
    }


//    /**
//     * @View(serializerGroups={"ids"})
//     */

    public function cgetAction()
    {
//        $data = [
//            'categories' => $this->getDoctrine()->getRepository('LokosShopBundle:Category')->findAll(),
//        ];
//        $view = $this->view($data, 200);
//
//        return $this->handleView($view);

        return [
            'categories' => $this->getDoctrine()->getRepository('LokosShopBundle:Category')->findAll(),
        ];
    }
}
