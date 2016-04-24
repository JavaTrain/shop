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

    public function indexAction(Request $request)
    {
        $this->getListData($request, 'repName');
    }

}