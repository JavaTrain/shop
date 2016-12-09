<?php

namespace Lokos\ShopBundle\Repositories;

use Doctrine\ORM\EntityManager;
use Lokos\ShopBundle\Entity\Product;
use Lokos\ShopBundle\Entity\Product2Option;

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
        $items = array();
        if (!empty($cart)) {
            $productIds = [];
            foreach($cart as $value){
                $productIds[] = $value->id;
            }
            /** @var Product $clone */
            $products = $this->getEntityManager()
                            ->getRepository('LokosShopBundle:Product')
                            ->reset()
                            ->buildQuery(['productIds' => array_unique($productIds)])
                            ->getList();
            foreach ($cart as $key => $item){
                foreach ($products as $product) {
                    if($product->getId() === $item->id){
                        $set = null;
                        if($product->getProductSets()->count()){
                            $setId = $item->productSet;
                            $set = $product->getProductSets()->filter(
                                function($entry) use ($setId){
                                    return $setId === $entry->getId();
                                }
                            );
                        }
                        $items[$key] = array(
                            'product'    => $product,
                            'quantity'   => $item->quantity,
                            'productSet' => $set,
                        );
                    }
                }
            }
        }

        return $items;
    }

    /**
     * @param array $cartItems
     *
     * @return array
     */
    public function getCartItemsCountAndPrice(array $cartItems)
    {
        $data          = array('total_quantity' => 0, 'total_price' => 0);
        $totalPrice    = 0;
        $totalQuantity = 0;

        if (!empty($cartItems)) {
            foreach ($cartItems as $item) {
                $priceItem = 0;
                $priceItem += abs((float)$item['product']->getPrice());
                if(!empty($item['productSet'])){
                    /** @var Product2Option $p2o */
                    foreach ($item['productSet']->first()->getProduct2Options() as $p2o){
                        $priceItem += abs((float)$p2o->getPrice());
                    }
                }

                $totalPrice += ($priceItem*$item['quantity']);
                $totalQuantity += $item['quantity'];
            }
            $data['total_quantity'] = $totalQuantity;
            $data['total_price']    = $totalPrice;
        }

        return $data;
    }
}