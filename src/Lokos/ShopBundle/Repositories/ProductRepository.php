<?php

namespace Lokos\ShopBundle\Repositories;

use Doctrine\ORM\Query\Expr\Join;
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
                ->addSelect('ps')
//                ->leftJoin('tbl.productSets', 'ps')
                ->leftJoin('tbl.productSets', 'ps')
                ->addSelect('p2o')
                ->leftJoin('ps.product2Options', 'p2o')
                ->addSelect('o')
                ->leftJoin('p2o.option', 'o')
                ->addSelect('ov')
                ->leftJoin('p2o.optionValue', 'ov')
                ->where('tbl.id = :id')
                ->setParameter(':id', $params['withOptions'])
//                ->andWhere('o_val.product = tbl.id')
            ;
        }
        if (!empty($params['id'])) {
            $this->query
                ->where('tbl.id = :id')
                ->setParameter(':id', $params['id']);
            ;
        }
        if (!empty($params['productSet'])) {
            $this->query
                ->andWhere('ps.id = :psId')
                ->setParameter(':psId', $params['productSet']);
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