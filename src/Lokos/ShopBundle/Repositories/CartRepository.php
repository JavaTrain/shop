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
//                if (count((array)$item->options)) {
                    $params['withOptions'] = $item->id;
                    if(!empty($item->productSet)){
                        $params['productSet'] = $item->productSet;
                    }
                    /** @var Product $product */
                    $product = $this->getEntityManager()
                                         ->getRepository('LokosShopBundle:Product')
                                         ->reset()
                                         ->buildQuery($params)
                                         ->getSingle();

//                    var_dump($product);die;
//                    if ($product) {
//                        $price = $product->getPrice();
//                        foreach ($product->getOptions() as $option) {
//                            foreach ($option->getOptionValues() as $value) {
//                                $price += (float)abs($value->getPrice());
//                            }
//                        }
////                        $price *= $item->quantity;
////                        $product->setCartPrice($price);
//                        $items[$key] = array(
//                            'product'    => $product,
//                            'quantity'   => $item->quantity,
//                            'productSet' => $item->productSet,
//                        );
//                    }
            }
        }
        var_dump($items);die;
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
//        var_dump($items);die;
        if (!empty($items)) {
            foreach ($items as $item) {
                $priceItem = 0;
                $priceItem += (float)abs($item['product']->getPrice());
                if(!empty($item['product']->getProductSets())){
                    foreach ($item['product']->getProductSets() as $ps){
                        /** @var ProductSet $ps */
                        /** @var Product2Option $p2o */
                        foreach ($ps->getProduct2Options() as $p2o){
                            $priceItem += abs((int)$p2o->getOptionValue()->getPrice());
                        }
                    }
                }

//                if (!empty($item['product']->getOptions())) {
//                    foreach ($item['product']->getOptions() as $option) {
//                        foreach ($option->getOptionValues() as $optionValue) {
//                            $priceItem += (float)abs($optionValue->getPrice());
//                        }
//                    }
//                }
                $totalPrice += $priceItem;
                $totalQuantity += $item['quantity'];
            }
            $data['total_quantity'] = $totalQuantity;
            $data['total_price']    = $totalPrice;
        }

//        var_dump($data);die;

        return $data;
    }
}