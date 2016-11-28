<?php

namespace Lokos\ShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * OrderDetail
 *
 * @ORM\Table(name="order_detail", indexes={@ORM\Index(name="fk_order_has_product1_order1_idx", columns={"order_id"}), @ORM\Index(name="fk_order_detail_product1_idx", columns={"product_id"})})
 * @ORM\Entity
 */
class OrderDetail
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
     * @var integer
     *
     * @ORM\Column(name="quantity", type="smallint", nullable=false)
     */
    private $quantity;

    /**
     * @var \Order
     *
     * @ORM\ManyToOne(targetEntity="Order")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     * })
     */
    private $order;

    /**
     * @var \Product
     *
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * })
     */
    private $product;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="OptionValue", inversedBy="orderDetail")
     * @ORM\JoinTable(name="option_value2order_detail",
     *   joinColumns={
     *     @ORM\JoinColumn(name="order_detail_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="option_value_id", referencedColumnName="id")
     *   }
     * )
     */
    private $optionValue;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->optionValue = new \Doctrine\Common\Collections\ArrayCollection();
    }


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
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return OrderDetail
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set order
     *
     * @param \Lokos\ShopBundle\Entity\Order $order
     *
     * @return OrderDetail
     */
    public function setOrder(\Lokos\ShopBundle\Entity\Order $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return \Lokos\ShopBundle\Entity\Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set product
     *
     * @param \Lokos\ShopBundle\Entity\Product $product
     *
     * @return OrderDetail
     */
    public function setProduct(\Lokos\ShopBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \Lokos\ShopBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Add optionValue
     *
     * @param \Lokos\ShopBundle\Entity\OptionValue $optionValue
     *
     * @return OrderDetail
     */
    public function addOptionValue(\Lokos\ShopBundle\Entity\OptionValue $optionValue)
    {
        $this->optionValue[] = $optionValue;

        return $this;
    }

    /**
     * Remove optionValue
     *
     * @param \Lokos\ShopBundle\Entity\OptionValue $optionValue
     */
    public function removeOptionValue(\Lokos\ShopBundle\Entity\OptionValue $optionValue)
    {
        $this->optionValue->removeElement($optionValue);
    }

    /**
     * Get optionValue
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOptionValue()
    {
        return $this->optionValue;
    }
    
    public function setOptionValues($optionValues)
    {
        $this->optionValue = $optionValues;
        
        return $this;
    }
}
