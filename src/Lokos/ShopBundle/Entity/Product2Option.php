<?php

namespace Lokos\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product2Option
 *
 * @ORM\Table(name="product2option")
 * @ORM\Entity(repositoryClass="Lokos\ShopBundle\Repositories\Product2OptionRepository")
 */
class Product2Option
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Product
     *
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="product2Options")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * })
     */
    private $product;

    /**
     * @var \Option
     *
     * @ORM\ManyToOne(targetEntity="Option")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="option_id", referencedColumnName="id")
     * })
     */
    private $option;

    /**
     * @var \ProductSet
     *
     * @ORM\ManyToOne(targetEntity="ProductSet", inversedBy="product2Options")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_set_id", referencedColumnName="id")
     * })
     */
    private $productSet;

    /**
     * @var \Option
     *
     * @ORM\ManyToOne(targetEntity="OptionValue")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="option_value_id", referencedColumnName="id")
     * })
     */
    private $optionValue;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", precision=10, scale=0, nullable=true)
     */
    private $price;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param $product
     *
     * @return $this
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return \Option
     */
    public function getOption()
    {
        return $this->option;
    }

    /**
     * @param $option
     *
     * @return $this
     */
    public function setOption($option)
    {
        $this->option = $option;

        return $this;
    }

    /**
     * @return \ProductSet
     */
    public function getProductSet()
    {
        return $this->productSet;
    }

    /**
     * @param $productSet
     *
     * @return $this
     */
    public function setProductSet($productSet)
    {
        $this->productSet = $productSet;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOptionValue()
    {
        return $this->optionValue;
    }

    /**
     * @param $optionValue
     *
     * @return $this
     */
    public function setOptionValue($optionValue)
    {
        $this->optionValue = $optionValue;

        return $this;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param $price
     *
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

//    /**
//     * @param $optionValues
//     *
//     * @return $this
//     */
//    public function setOptionValues($optionValues)
//    {
//        $this->optionValues = $optionValues;
//
//        return $this;
//    }

//    /**
//     * @param OptionValue $optionValue
//     *
//     * @return $this
//     */
//    public function addProduct2options(\Lokos\ShopBundle\Entity\OptionValue $optionValue)
//    {
//        $this->optionValues[] = $optionValue;
//
//        return $this;
//    }
//
//    /**
//     * @param OptionValue $optionValue
//     *
//     * @return $this
//     */
//    public function removeOptionValues(\Lokos\ShopBundle\Entity\OptionValue $optionValue)
//    {
//        $this->optionValues->removeElement($optionValue);
//
//        return $this;
//    }


}
