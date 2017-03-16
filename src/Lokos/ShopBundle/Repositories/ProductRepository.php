<?php

namespace Lokos\ShopBundle\Repositories;

use Doctrine\ORM\Query\Expr\Join;
use Lokos\ShopBundle\Entity\Product;
use Lokos\ShopBundle\Entity\Product2Option;
use Lokos\ShopBundle\Entity\ProductSet;
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
        foreach ($filterAttributes as $attr) {
            foreach ($attr as $v) {
                array_push($attributeValuesIds, $v);
            }
        }

        $qb  = $this->createQueryBuilder('tbl');
        $res = $qb->select('tbl.id')
                  ->leftJoin('tbl.product2Attributes', 'p2a')
                  ->leftJoin('p2a.attributeValue', 'av')
                  ->where($qb->expr()->eq('tbl.category', ':category'))
                  ->andWhere($qb->expr()->in('av.id', ':attributeValuesIds'))
                  ->setParameter(':category', $catId)
                  ->setParameter(':attributeValuesIds', $attributeValuesIds)
                  ->groupBy('tbl.id')
                  ->andHaving($qb->expr()->eq($qb->expr()->count('tbl.id'), ':productCount'))
                  ->setParameter(':productCount', count($filterAttributes));
        if (!empty($filterBrand)) {
            $res = $res->andWhere($qb->expr()->in('tbl.brand', ':brandIds'))
                       ->setParameter(':brandIds', $filterBrand);
        }

        $res = $res->getQuery()->getResult();

        return array_map('current', $res);
    }


    /**
     * @param       $catId
     * @param array $filterBrand
     * @param array $filterOptions
     *
     * @return array
     */
    public function getIdsByFilterOptions($catId, array $filterBrand, array $filterOptions)
    {
        $optionValuesIds = array();
        foreach ($filterOptions as $option) {
            foreach ($option as $v) {
                array_push($optionValuesIds, $v);
            }
        }

        $qb  = $this->createQueryBuilder('tbl');
        $res = $qb->select('tbl.id')
                  ->leftJoin('tbl.productSets', 'ps')
                  ->leftJoin('ps.product2Options', 'p2o')
                  ->leftJoin('p2o.optionValue', 'ov')
                  ->where($qb->expr()->eq('tbl.category', ':category'))
                  ->andWhere($qb->expr()->in('ov.id', ':optionValuesIds'))
                  ->setParameter(':category', $catId)
                  ->setParameter(':optionValuesIds', $optionValuesIds)
                  ->groupBy('tbl.id')
                  ->andHaving($qb->expr()->eq($qb->expr()->count('tbl.id'), ':productCount'))
                  ->setParameter(':productCount', count($filterOptions));
        if (!empty($filterBrand)) {
            $res = $res->andWhere($qb->expr()->in('tbl.brand', ':brandIds'))
                       ->setParameter(':brandIds', $filterBrand);
        }

        $res = $res->getQuery()->getResult();

        return array_map('current', $res);
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