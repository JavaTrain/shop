<?php

namespace Lokos\ShopBundle\Repositories;

use Doctrine\ORM\EntityManager;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

class CategoryRepository extends NestedTreeRepository
{

    /**
     * @var \Doctrine\ORM\QueryBuilder
     */
    protected $query;

    public function __construct(EntityManager $em, ClassMetadata $class)
    {
        parent::__construct($em, $class);

//        $this->initializeTreeRepository($em, $class);
    }

    /**
     * Build query
     *
     * @param array $params
     *
     * @return $this
     */
    public function buildQuery($params = array())
    {
        if (!$this->query) {
            $this->query = $this->createQueryBuilder('tbl');
        }

        if (!empty($params['id'])) {
            $this->query
                ->addSelect('b')
                ->join('tbl.brands', 'b')
                ->addSelect('a')
                ->join('tbl.attributes', 'a')
                ->where('tbl.id = :id')
                ->setParameter(':id', $params['id'])
            ;
        }

        return $this;
    }

    /**
     * Reset query parameters
     *
     * @return $this
     */
    public function reset()
    {
        $this->query = $this->createQueryBuilder('tbl');

        return $this;
    }

    public function getList($limit = 0, $offset = 0, $order = null, $sort = 'asc')
    {
        if (!empty($limit)) {
            $this->query
                ->setMaxResults($limit);
        }
        if (!empty($offset)) {
            $this->query
                ->setFirstResult($offset);
        }
        if (!empty($order) && !empty($sort)) {
            if (is_array($order)) {
                foreach ($order as $key => $val) {
                    if (strpos($key, '.') === false) {
                        $key = 'tbl.'.$key;
                    }
                    $this->query
                        ->addOrderBy($key, $val);
                }
            } else {
                $order = explode(',', $order);
                array_walk(
                    $order,
                    function (&$item) {
                        if (strpos($item, '.') === false) {
                            $item = 'tbl.'.trim($item);
                        }
                    }
                );
                foreach ($order as $i) {
                    $this->query->addOrderBy($i, $sort);
                }
            }
        }
        //        print_r($this->query->getQuery()->getSQL());
        ?><!--<pre>--><?php //print_r($this->query->getQuery()->getParameters());die;
        return $this->query
            ->getQuery()
            ->getResult();
    }

    /**
     * Get single item
     *
     * @return mixed
     */
    public function getSingle()
    {
        //        print_r($this->query->getQuery()->getSQL());var_dump($this->query->getQuery()->getParameters());die;
        return $this->query
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @inheritdoc
     */
    public function remove(ContainerInterface $container, $id)
    {
    }

}