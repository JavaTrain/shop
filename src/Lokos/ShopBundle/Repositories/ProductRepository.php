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
            ->addSelect('o')
            ->leftJoin('p2o.option', 'o')
            ->addSelect('ov')
            ->leftJoin('p2o.optionValue', 'ov')
            ->addSelect('p2a')
            ->leftJoin('tbl.product2Attributes', 'p2a')
//            ->addSelect('a')
//            ->leftJoin('p2a.attribute', 'a')
//            ->addSelect('av')
//            ->leftJoin('p2a.attributeValue', 'av')
        ;
        if (!empty($params['categoryId'])) {
            $this->query
                ->andWhere('tbl.category = :category')
                ->setParameter(':category', $params['categoryId']);
        }
        if (!empty($params['productIds'])) {
            $this->query
                ->andWhere('tbl.id IN (:ids)')
                ->setParameter(':ids', $params['productIds'])
            ;
        }
        if (!empty($params['productId'])) {
            $this->query
                ->where('tbl.id = :productId')
                ->setParameter(':productId', $params['productId']);
            ;
        }
        if (!empty($params['productSet'])) {
            $this->query
                ->andWhere('ps.id = :psId')
                ->setParameter(':psId', $params['productSet']);
            ;
        }
        if (!empty($params['filterBrand'])) {
            $this->query
                ->andWhere($this->query->expr()->in('tbl.brand', ':brandIds'))
                ->setParameter(':brandIds', $params['filterBrand']);
            ;
        }
        if (!empty($params['filterAttribute'])) {
            $i=0;
            foreach($params['filterAttribute'] as $attrId => $attrValuesIds){
                $i++;
                $this->query
                    ->andWhere('a.id = :attrId'.$i)
                    ->setParameter(':attrId'.$i, $attrId)
                    ->andWhere($this->query->expr()->in('av.id', ':attrValuesIds'.$i))
                    ->setParameter(':attrValuesIds'.$i, $attrValuesIds);
            }

        }
        if (!empty($params['filterOption'])) {
            $i=0;
            foreach($params['filterOption'] as $optionId => $optionValuesIds){
                $i++;
                $this->query
                    ->andWhere('o.id = :optionId'.$i)
                    ->setParameter(':optionId'.$i, $optionId)
                    ->andWhere($this->query->expr()->in('ov.id', ':optionValuesIds'.$i))
                    ->setParameter(':optionValuesIds'.$i, $optionValuesIds);
            }
        }
        
        return $this;
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
                    'optionId' => $p2o->getOption()->getId(),
                    'valueId'  => $p2o->getOptionValue()->getId(),
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
                    if (!array_key_exists($entry->getOption()->getId(), $optionFilter)) {
                        $options[$entry->getOption()->getId()]      = $entry->getOption();
                        $optionFilter[$entry->getOption()->getId()] = $entry->getOption()->getId();
                        if (!array_key_exists($entry->getOptionValue()->getId(), $valueFilter)) {
                            $valueFilter[$entry->getOptionValue()->getId()] = $entry->getOptionValue()->getId();
                            $values[$entry->getOption()->getId()][]         = $entry->getOptionValue();
                        }
                        return true;
                    } else {
                        if(!array_key_exists($entry->getOptionValue()->getId(), $valueFilter)){
                            $entry->getOptionValue()->setPrice($entry->getPrice());
                            $valueFilter[$entry->getOptionValue()->getId()] = $entry->getOptionValue()->getId();
                            $values[$entry->getOption()->getId()][] = $entry->getOptionValue();
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