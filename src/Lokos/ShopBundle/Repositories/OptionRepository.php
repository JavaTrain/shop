<?php

namespace Lokos\ShopBundle\Repositories;

use Lokos\ShopBundle\Entity\Product;
use Symfony\Component\DependencyInjection\ContainerInterface;


class OptionRepository extends BaseRepository
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

//        $this->query->select(array('tbl'));
//        if (!empty($params[':'])) {
//            $this->query
//                ->addSelect('o_val')
//                ->join('tbl.optionValues', 'o_val')
//                ->addSelect('p2o_val')
//                ->join('o_val.product2optionValues', 'p2o_val')
//                ->where('p2o_val.product = :product_id')
//                ->setParameter(':product_id', $params['product'])
//            ;
//        }
        

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function remove(ContainerInterface $container, $id)
    {
    }

}