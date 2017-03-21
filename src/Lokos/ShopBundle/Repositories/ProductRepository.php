<?php

namespace Lokos\ShopBundle\Repositories;

use Doctrine\ORM\Query\Expr\Join;
use Lokos\ShopBundle\Entity\Product;
use Lokos\ShopBundle\Entity\Product2Option;
use Lokos\ShopBundle\Entity\ProductSet;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ProductRepository
 *
 * @package Lokos\ShopBundle\Repositories
 */
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

        $this->query->select(array('tbl'))
            ->addSelect('ps')
            ->leftJoin('tbl.productSets', 'ps')
            ->addSelect('p2o')
            ->leftJoin('ps.product2Options', 'p2o')
            ->addSelect('p2a')
            ->leftJoin('tbl.product2Attributes', 'p2a')
            ->addSelect('a')
            ->leftJoin('p2a.attribute', 'a')
            ->addSelect('av')
            ->leftJoin('p2a.attributeValue', 'av')
        ;
        if (!empty($params['categoryId'])) {
            $this->query
                ->andWhere($this->query->expr()->eq('tbl.category' ,':category'))
                ->setParameter(':category', $params['categoryId']);
        }
        if (!empty($params['productIds'])) {
            $this->query
                ->andWhere($this->query->expr()->in('tbl.id', ':ids'))
                ->setParameter(':ids', $params['productIds'])
            ;
        }
        if (!empty($params['productId'])) {
            $this->query
                ->where($this->query->expr()->eq('tbl.id', ':productId'))
                ->setParameter(':productId', $params['productId']);
            ;
        }
        if (!empty($params['productSet'])) {
            $this->query
                ->andWhere($this->query->expr()->eq('ps.id', ':psId'))
                ->setParameter(':psId', $params['productSet']);
            ;
        }
        if (!empty($params['filterBrand'])) {
            $this->query
                ->andWhere($this->query->expr()->in('tbl.brand', ':brandIds'))
                ->setParameter(':brandIds', $params['filterBrand']);
            ;
        }
        
        return $this;
    }

    /**
     * @param       $catId
     * @param array $filterBrand
     * @param array $filterAttributes
     *
     * @return array
     */
    public function getIdsByFilterAttributes($catId, array $filterBrand, array $filterAttributes)
    {
        $attributeValuesIds = array();
        $cnt                = array();
        foreach ($filterAttributes as $k => $attr) {
            foreach ($attr as $v) {
                array_push($attributeValuesIds, $v);
                $cnt[$k] = $v;
            }
        }
        $attrCount = count($cnt);

        $qb         = $this->createQueryBuilder('tbl');
        $attributes = $qb->select('tbl.id AS product_id', 'COUNT(av.id) AS attr_cnt')
                         ->leftJoin('tbl.product2Attributes', 'p2a')
                         ->leftJoin('p2a.attributeValue', 'av')
                         ->where($qb->expr()->eq('tbl.category', ':category'))
                         ->andWhere($qb->expr()->in('av.id', ':attributeValuesIds'))
                         ->groupBy('tbl.id')
                         ->setParameter(':category', $catId)
                         ->setParameter(':attributeValuesIds', $attributeValuesIds);
        if (!empty($filterBrand)) {
            $attributes = $attributes->andWhere($qb->expr()->in('tbl.brand', ':brandIds'))
                       ->setParameter(':brandIds', $filterBrand);
        }
//        print_r($atrributes->getQuery()->getSQL());
//        print_r($atrributes->getQuery()->getParameters());
//        die;
        $attributes = $attributes->getQuery()->getResult();
//        var_dump($attributes);//die;
        $attrs = array();
        foreach($attributes as $v) {
            $attrs[$v['product_id']] = $v['attr_cnt'];
        }

//        var_dump($attrs);//die;

        $qb      = $this->createQueryBuilder('tbl');
        $options = $qb->select('tbl.id AS product_id', 'COUNT(av.id) AS attr_cnt', 'ps.id AS product_set_id')
                      ->leftJoin('tbl.productSets', 'ps')
                      ->leftJoin('ps.product2Options', 'p2o')
                      ->leftJoin('p2o.attributeValue', 'av')
                      ->where($qb->expr()->eq('tbl.category', ':category'))
                      ->andWhere($qb->expr()->in('av.id', ':attributeValuesIds'))
                      ->setParameter(':category', $catId)
                      ->setParameter(':attributeValuesIds', $attributeValuesIds)
                      ->groupBy('ps.id');
        if (!empty($filterBrand)) {
            $options = $options->andWhere($qb->expr()->in('tbl.brand', ':brandIds'))
                               ->setParameter(':brandIds', $filterBrand);
        }
//        print_r($options->getQuery()->getSQL());
//        print_r($options->getQuery()->getParameters());
//        die;
        $options = $options->getQuery()->getResult();

//        var_dump($options);die;
        $result = array();
        if (!empty($options) && !empty($attrs)) {
            foreach ($options as $opt) {
                if (!empty($attrs[$opt['product_id']]) && ($attrs[$opt['product_id']] + $opt['attr_cnt'] == $attrCount)) {
                    $result[$opt['product_id']] = $opt['product_id'];
                } else {
                    if (!empty($attrs[$opt['product_id']]) && $attrs[$opt['product_id']] == $attrCount) {
                        $result[$opt['product_id']] = $opt['product_id'];
                    }
                }
            }
        } elseIf (!empty($options) && empty($attrs)) { //if filters only in options
            foreach ($options as $opt) {
                if ($opt['attr_cnt'] == $attrCount) {
                    $result[$opt['product_id']] = $opt['product_id'];
                }
            }
        } elseif (empty($options) && !empty($attrs)) {
            foreach ($attrs as $productId => $cnt) {
                if ($cnt == $attrCount) {
                    $result[$productId] = $productId;
                }
            }
        }

//        var_dump($result);die;

        return empty($result)?array('-1'):array_keys($result);
    }

    /**
     * @param Product $item
     *
     * @return array
     */
    public function getAvailableOptions(Product $item)
    {
        if(!$item->getProductSets()){
            return null;
        }

        $options = [];
        /** @var ProductSet $ps */
        /** @var Product2Option $p2o */
        foreach($item->getProductSets() as $ps){
            foreach ($ps->getProduct2Options() as $p2o) {
                $options[$ps->getId()][] = [
                    'optionId' => $p2o->getAttribute()->getId(),
                    'valueId'  => $p2o->getAttributeValue()->getId(),
                ];
            }
        }

        //        var_dump($options);die;
        return $options;
    }

    /**
     * @param Product $item
     *
     * @return array|null
     */
    public function getProductOptions(Product $item)
    {
        if(!$item->getProductSets()){
            return null;
        }

        $options      = [];
        $optionFilter = [];
        $values       = [];
        $valueFilter  = [];
        /** @var ProductSet $ps */
        foreach ($item->getProductSets() as $ps) {
            $ps->getProduct2Options()->filter(
                function ($entry) use (&$optionFilter, &$options, &$values, &$valueFilter) {
                    /** @var Product2Option $entry */
                    if (!array_key_exists($entry->getAttribute()->getId(), $optionFilter)) {
                        $options[$entry->getAttribute()->getId()]      = $entry->getAttribute();
                        $optionFilter[$entry->getAttribute()->getId()] = $entry->getAttribute()->getId();
                        if (!array_key_exists($entry->getAttributeValue()->getId(), $valueFilter)) {
                            $entry->getAttributeValue()->setPrice($entry->getPrice());
                            $valueFilter[$entry->getAttributeValue()->getId()] = $entry->getAttributeValue()->getId();
                            $values[$entry->getAttribute()->getId()][]         = $entry->getAttributeValue();
                        }

                        return true;
                    } else {
                        if(!array_key_exists($entry->getAttributeValue()->getId(), $valueFilter)){
                            $entry->getAttributeValue()->setPrice($entry->getPrice());
                            $valueFilter[$entry->getAttributeValue()->getId()] = $entry->getAttributeValue()->getId();
                            $values[$entry->getAttribute()->getId()][] = $entry->getAttributeValue();
                        }

                        return false;
                    }
                }
            );
        }
//        var_dump($options, $values);

        return [$options, $values];
    }

    /**
     * @inheritdoc
     */
    public function remove(ContainerInterface $container, $id)
    {
    }

}