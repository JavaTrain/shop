<?php

namespace Lokos\ShopBundle\Repositories;

use Lokos\ShopBundle\Entity\Product;
use Symfony\Component\DependencyInjection\ContainerInterface;


class ProductRepository extends BaseRepository
{

    /**
     * Build query
     *
     * @param array $params
     *
     * @return $this
     */
    public function buildQuery($params = array())
    {
        parent::buildQuery();

        $this->query->select(array('tbl'));
        if (!empty($params['categoryId'])) {
            $this->query
                ->andWhere('tbl.category = :category')
                ->setParameter(':category', $params['categoryId'])
            ;
        }
        if (!empty($params['withOptions'])) {
            $this->query
                ->addSelect('o')
                ->join('tbl.options', 'o')
                ->addSelect('o_val')
                ->join('o.optionValues', 'o_val')
                ->where('tbl.id = :id')
                ->setParameter(':id', $params['withOptions'])
                ->andWhere('o_val.product = tbl.id')
            ;
        }
        if (!empty($params['id'])) {
            $this->query
                ->where('tbl.id = :id')
                ->setParameter(':id', $params['id']);
            ;
        }
        
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function remove(ContainerInterface $container, $id)
    {
    }

//    public function getCartCountAndPrice(array $cart)
//    {
//        $data = array();
//        if (!empty($cart)) {
//            $items = $this->getEntityManager()->getRepository('LokosShopBundle:Product')
//                          ->reset()
//                          ->buildQuery(
//                              array('products' => array_keys($cart))
//                          )
//                          ->getList();
//            $price = 0;
//            /** @var Product $item */
//            foreach ($items as $item) {
//                $price += ($item->getPrice() * $cart[$item->getId()]);
//            }
//            $data['sum']   = array_sum($cart);
//            $data['price'] = $price;
//        }
//        return $data;
//    }

}