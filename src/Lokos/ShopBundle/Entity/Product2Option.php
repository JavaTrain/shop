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
     * @var Attribute
     *
     * @ORM\ManyToOne(targetEntity="Attribute")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="attribute_id", referencedColumnName="id")
     * })
     */
    private $attribute;

    /**
     * @var ProductSet
     *
     * @ORM\ManyToOne(targetEntity="ProductSet", inversedBy="product2Options")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_set_id", referencedColumnName="id")
     * })
     */
    private $productSet;

    /**
     * @var AttributeValue
     *
     * @ORM\ManyToOne(targetEntity="AttributeValue")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="attribute_value_id", referencedColumnName="id")
     * })
     */
    private $attributeValue;

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
     * @return Attribute
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * @param Attribute $attribute
     *
     * @return $this
     */
    public function setAttribute(Attribute $attribute)
    {
        $this->attribute = $attribute;

        return $this;
    }

    /**
     * @return ProductSet
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
    public function setProductSet(ProductSet $productSet)
    {
        $this->productSet = $productSet;

        return $this;
    }

    /**
     * @return AttributeValue
     */
    public function getAttributeValue()
    {
        return $this->attributeValue;
    }

    /**
     * @param AttributeValue $attributeValue
     *
     * @return $this
     */
    public function setAttributeValue(AttributeValue $attributeValue)
    {
        $this->attributeValue = $attributeValue;

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
}
