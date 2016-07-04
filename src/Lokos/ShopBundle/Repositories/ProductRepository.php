<?php

namespace Lokos\ShopBundle\Repositories;

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

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function remove(ContainerInterface $container, $id)
    {
    }

    /**
     * @return array
     */
    public function getCategories()
    {
        return $this->getEntityManager()
            ->getRepository('LokosShopBundle:Category')
            ->findAll();
    }

}