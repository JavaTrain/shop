<?php

namespace Lokos\ShopBundle\Repositories;

use Symfony\Component\DependencyInjection\ContainerInterface;


class AttributeRepository extends BaseRepository
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