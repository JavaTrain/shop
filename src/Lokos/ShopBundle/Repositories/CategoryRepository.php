<?php

namespace Lokos\ShopBundle\Repositories;

use Symfony\Component\DependencyInjection\ContainerInterface;


class CategoryRepository extends BaseRepository
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

        if (!empty($params['id'])) {
            $this->query
                ->addSelect('b')
                ->join('tbl.brands', 'b')
                ->addSelect('a')
                ->join('tbl.attributes', 'a')
                ->where('tbl.id = :id')
                ->addSelect('o')
                ->join('tbl.option', 'o')
                ->setParameter(':id', $params['id'])
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