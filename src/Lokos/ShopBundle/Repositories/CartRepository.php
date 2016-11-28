<?php

namespace Lokos\ShopBundle\Repositories;

use Doctrine\ORM\EntityManager;
use Lokos\ShopBundle\Entity\Product;

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
            foreach ($cart as $key => $item) {
                if (count((array)$item->options)) {
                    /** @var Product $product */
                    $product = $this->getEntityManager()
                                         ->getRepository('LokosShopBundle:Product')
                                         ->reset()
                                         ->buildQuery(
                                             array(
                                                 'withOptions' => $item->id
                                             )
                                         )
                                         ->getSingle();
                    if ($product) {
                        $price = $product->getPrice();
                        foreach ($product->getOptions() as $option) {
                            foreach ($option->getOptionValues() as $value) {
                                $price += (float)abs($value->getPrice());
                            }
                        }
                        $price *= $item->quantity;
                        $product->setCartPrice($price);
                        $items[$key] = array(
                            'product'  => $product,
                            'quantity' => $item->quantity,
                            'options'  => $item->options,
                        );
                    }
                } else {
                    $product = $this->getEntityManager()
                        ->getRepository('LokosShopBundle:Product')
                        ->reset()
                        ->buildQuery(
                            array(
                                'id' => $item->id,
                            )
                        )
                        ->getSingle();
                    if ($product) {
                        $product->setCartPrice(($product->getPrice() * $item->quantity));
                        $items[$key] = array(
                            'product'  => $product,
                            'quantity' => $item->quantity,
                            'options'  => $item->options,
                        );
                        $items[$key] = array(
                            'product'  => $product,
                            'quantity' => $item->quantity,
                            'options'  => new \stdClass(),
                        );
                    }
                }
            }
        }
        
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
                $priceItem += (float)abs($item['product']->getPrice());
                if (!empty($item['product']->getOptions())) {
                    foreach ($item['product']->getOptions() as $option) {
                        foreach ($option->getOptionValues() as $optionValue) {
                            $priceItem += (float)abs($optionValue->getPrice());
                        }
                    }
                }
                $totalPrice += ($priceItem);
                $totalQuantity += $item['quantity'];
            }
            $data['total_quantity'] = $totalQuantity;
            $data['total_price']    = $totalPrice;
        }

        return $data;
    }
}