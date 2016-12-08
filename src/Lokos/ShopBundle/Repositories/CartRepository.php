<?php

namespace Lokos\ShopBundle\Repositories;

use Doctrine\ORM\EntityManager;
use Lokos\ShopBundle\Entity\Product;
use Lokos\ShopBundle\Entity\Product2Option;
use Lokos\ShopBundle\Entity\ProductSet;

class CartRepository
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager() {
        return $this->em;
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    public function getConnection() {
        return $this->getEntityManager()->getConnection();
    }

    /**
     * @param array $cart
     *
     * @return array
     */
    public function getCartItems(array $cart)
    {
//        var_dump($cart);die;
        $items = array();
        if (!empty($cart)) {
            foreach ($cart as $key => $item) {
//                var_dump($item);die;
                $params['withOptions'] = $item->id;
//                if(!empty($item->productSet)){
                $params['productSet'] = $item->productSet;
//                }

                // if prodSet don't added get all prodset !!!!!!!

                /** @var Product $product */
                $product = $this->getEntityManager()
                                ->getRepository('LokosShopBundle:Product')
                                ->reset()
                                ->buildQuery($params)
                                ->getSingle();

                    $items[$key] = array(
                        'product'    => $product,
                        'quantity'   => $item->quantity,
                        'productSet' => $item->productSet,
                    );
            }
        }
//        var_dump($items);die;
        return $items;
    }

    /**
     * @param array $items
     *
     * @return array
     */
    public function getCartItemsCountAndPrice(array $items)
    {
        $data          = array('total_quantity' => 0, 'total_price' => 0);
        $totalPrice    = 0;
        $totalQuantity = 0;

        if (!empty($items)) {
            foreach ($items as $item) {
                $priceItem = 0;
                $priceItem += abs((float)$item['product']->getPrice());
                if(!empty($item['product']->getProductSets())){
                    foreach ($item['product']->getProductSets() as $ps){
                        /** @var ProductSet $ps */
                        /** @var Product2Option $p2o */
                        foreach ($ps->getProduct2Options() as $p2o){
                            $priceItem += abs((float)$p2o->getPrice());
                        }
                    }
                }

                $totalPrice += ($priceItem*$item['quantity']);
                $totalQuantity += $item['quantity'];
            }
            $data['total_quantity'] = $totalQuantity;
            $data['total_price']    = $totalPrice;
        }

//        var_dump($data);die;

        return $data;
    }
}