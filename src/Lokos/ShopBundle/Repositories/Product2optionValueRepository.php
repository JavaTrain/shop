<?php

namespace Lokos\ShopBundle\Repositories;

use Lokos\ShopBundle\Entity\Product;
use Symfony\Component\DependencyInjection\ContainerInterface;


class Product2optionValueRepository extends BaseRepository
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
        if (!empty($params['options'])) {
            $i=0;
            foreach ($params['options']->options as $optionId => $optionValueId) {
                $i++;
                $this->query
                    ->addSelect('o_val')
                    ->join('tbl.optionValue', 'o_val')
                    ->addSelect('o')
                    ->join('o_val.option', 'o')
                    ->andWhere("o.id = :optionId{$i}")
                    ->setParameter(":optionId{$i}", $optionId)
                    ->andWhere('o_val.id = :o_val_id')
                    ->setParameter(':o_val_id', $optionValueId)
                    ->andWhere('tbl.product = :product_id')
                    ->setParameter(':product_id', $params['options']->id)
                ;
            }
        }
        if (!empty($params['allOptions'])) {
            $this->query
                ->addSelect('o_val')
                ->join('tbl.optionValue', 'o_val')
                ->addSelect('o')
                ->join('o_val.option', 'o')
                ->andWhere('tbl.product = :product')
                ->setParameter(':product', $params['allOptions'])
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

}