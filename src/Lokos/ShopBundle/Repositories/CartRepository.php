<?php

namespace Lokos\ShopBundle\Repositories;

use Lokos\ShopBundle\Entity\Product;
use Symfony\Component\DependencyInjection\ContainerInterface;


class CartRepository extends BaseRepository
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
        
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function remove(ContainerInterface $container, $id)
    {
    }
    
    

}