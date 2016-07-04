<?php

namespace Lokos\ShopBundle\Repositories;

use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base Repository
 */
abstract class BaseRepository extends EntityRepository
{
    /**
     * @var \Doctrine\ORM\QueryBuilder
     */
    protected $query;

    /**
     * Builds query
     *
     * @param array $params parameters for search
     *
     * @return $this
     */
    public function buildQuery($params = array())
    {
        if (!$this->query) {
            $this->query = $this->createQueryBuilder('tbl');
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

    /**
     * Returns a list of items
     *
     * @param int          $limit max count of result
     * @param int          $offset
     * @param string|array $sort
     * @param string       $order
     *
     * @return array
     */
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
        return $this->query
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Returns count of items
     *
     * @return int count of all members
     */
    public function getCount()
    {
        $query = clone $this->query;
        $result = $query
            ->select(array('COUNT(tbl.id)'))
            ->setFirstResult(null)
            ->setMaxResults(null)
            ->getQuery()
            ->getOneOrNullResult();

        return $result?current($result):0;
    }

    /**
     * Remove items
     *
     * @param ContainerInterface $container
     * @param mixed              $id retailers id
     *
     * @return bool true if success, else false
     */
    abstract function remove(ContainerInterface $container, $id);
}