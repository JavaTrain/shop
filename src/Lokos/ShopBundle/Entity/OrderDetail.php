<?php

namespace Lokos\ShopBundle\Entity;

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
     * Many Features have One Product.
     * @ORM\ManyToOne(targetEntity="ProductSet", inversedBy="orderDetails")
     * @ORM\JoinColumn(name="product_set_id", referencedColumnName="id")
     */
    private $productSet;


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
    public function setOrder(Order $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return \Order
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
    public function setProduct(Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return \Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Get optionValue
     *
     * @return \Doctrine\Common\Collections\Collection
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
}
