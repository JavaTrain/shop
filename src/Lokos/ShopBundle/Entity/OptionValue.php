<?php

namespace Lokos\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OptionValue
 *
 * @ORM\Table(name="option_value", indexes={@ORM\Index(name="fk_option_detail_option1_idx", columns={"option_id"}), @ORM\Index(name="fk_option_value_product1_idx", columns={"product_id"})})
 * @ORM\Entity(repositoryClass="Lokos\ShopBundle\Repositories\OptionValueRepository")
 */
class OptionValue
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
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=45, nullable=false)
     */
    private $value;

    /**
     * @var \Option
     *
     * @ORM\ManyToOne(targetEntity="Option", inversedBy="optionValues")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="option_id", referencedColumnName="id")
     * })
     */
    private $option;

    /**
     * @var \Product
     *
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="optionValues")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * })
     */
    private $product;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="OrderDetail", mappedBy="optionValue")
     */
    private $orderDetail;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->orderDetail = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set value
     *
     * @param string $value
     *
     * @return OptionValue
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set option
     *
     * @param \Lokos\ShopBundle\Entity\Option $option
     *
     * @return OptionValue
     */
    public function setOption(\Lokos\ShopBundle\Entity\Option $option = null)
    {
        $this->option = $option;

        return $this;
    }

    /**
     * Get option
     *
     * @return \Lokos\ShopBundle\Entity\Option
     */
    public function getOption()
    {
        return $this->option;
    }

    /**
     * Set product
     *
     * @param \Lokos\ShopBundle\Entity\Product $product
     *
     * @return OptionValue
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
     * Add orderDetail
     *
     * @param \Lokos\ShopBundle\Entity\OrderDetail $orderDetail
     *
     * @return OptionValue
     */
    public function addOrderDetail(\Lokos\ShopBundle\Entity\OrderDetail $orderDetail)
    {
        $this->orderDetail[] = $orderDetail;

        return $this;
    }

    /**
     * Remove orderDetail
     *
     * @param \Lokos\ShopBundle\Entity\OrderDetail $orderDetail
     */
    public function removeOrderDetail(\Lokos\ShopBundle\Entity\OrderDetail $orderDetail)
    {
        $this->orderDetail->removeElement($orderDetail);
    }

    /**
     * Get orderDetail
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrderDetail()
    {
        return $this->orderDetail;
    }

    /**
     * @return string
     */
    function __toString()
    {
        return $this->value;
    }
}
