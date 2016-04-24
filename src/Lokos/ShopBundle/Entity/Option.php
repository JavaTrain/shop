<?php

namespace Lokos\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Option
 *
 * @ORM\Table(name="`option`", indexes={@ORM\Index(name="fk_option_atribute1_idx", columns={"atribute_id"})})
 * @ORM\Entity
 */
class Option
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

//    /**
//     * @var string
//     *
//     * @ORM\Column(name="name", type="string", length=45, nullable=false)
//     */
//    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var \Atribute
     *
     * @ORM\ManyToOne(targetEntity="Atribute")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="atribute_id", referencedColumnName="id")
     * })
     */
    private $atribute;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Order", mappedBy="option")
     */
    private $order;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Product", mappedBy="option")
     */
    private $product;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->order = new \Doctrine\Common\Collections\ArrayCollection();
        $this->product = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Option
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

//    /**
//     * Set name
//     *
//     * @param string $name
//     *
//     * @return Option
//     */
//    public function setName($name)
//    {
//        $this->name = $name;
//
//        return $this;
//    }
//
//    /**
//     * Get name
//     *
//     * @return string
//     */
//    public function getName()
//    {
//        return $this->name;
//    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Option
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set atribute
     *
     * @param \Lokos\ShopBundle\Entity\Atribute $atribute
     *
     * @return Option
     */
    public function setAtribute(\Lokos\ShopBundle\Entity\Atribute $atribute = null)
    {
        $this->atribute = $atribute;

        return $this;
    }

    /**
     * Get atribute
     *
     * @return \Lokos\ShopBundle\Entity\Atribute
     */
    public function getAtribute()
    {
        return $this->atribute;
    }

    /**
     * Add order
     *
     * @param \Lokos\ShopBundle\Entity\Order $order
     *
     * @return Option
     */
    public function addOrder(\Lokos\ShopBundle\Entity\Order $order)
    {
        $this->order[] = $order;

        return $this;
    }

    /**
     * Remove order
     *
     * @param \Lokos\ShopBundle\Entity\Order $order
     */
    public function removeOrder(\Lokos\ShopBundle\Entity\Order $order)
    {
        $this->order->removeElement($order);
    }

    /**
     * Get order
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Add product
     *
     * @param \Lokos\ShopBundle\Entity\Product $product
     *
     * @return Option
     */
    public function addProduct(\Lokos\ShopBundle\Entity\Product $product)
    {
        $this->product[] = $product;

        return $this;
    }

    /**
     * Remove product
     *
     * @param \Lokos\ShopBundle\Entity\Product $product
     */
    public function removeProduct(\Lokos\ShopBundle\Entity\Product $product)
    {
        $this->product->removeElement($product);
    }

    /**
     * Get product
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProduct()
    {
        return $this->product;
    }
}
