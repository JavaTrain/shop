<?php

namespace Lokos\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="product", indexes={@ORM\Index(name="fk_product_category_idx", columns={"category_id"}), @ORM\Index(name="fk_product_brand1_idx", columns={"brand_id"})})
 * @ORM\Entity
 */
class Product
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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=16777215, nullable=false)
     */
    private $description;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", precision=10, scale=0, nullable=false)
     */
    private $price;

    /**
     * @var \Category
     *
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * })
     */
    private $category;

    /**
     * @var \Brand
     *
     * @ORM\ManyToOne(targetEntity="Brand")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="brand_id", referencedColumnName="id")
     * })
     */
    private $brand;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Atribute", mappedBy="product")
     */
    private $atribute;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Option", inversedBy="product")
     * @ORM\JoinTable(name="product2option",
     *   joinColumns={
     *     @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="option_id", referencedColumnName="id")
     *   }
     * )
     */
    private $option;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Order", mappedBy="product")
     */

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="News", inversedBy="channels")
     * @ORM\JoinColumn(name="news_id", referencedColumnName="id")
     */
    private $order;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->atribute = new \Doctrine\Common\Collections\ArrayCollection();
        $this->option = new \Doctrine\Common\Collections\ArrayCollection();
        $this->order = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Product
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
     * Set price
     *
     * @param float $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set category
     *
     * @param \Lokos\ShopBundle\Entity\Category $category
     *
     * @return Product
     */
    public function setCategory(\Lokos\ShopBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Lokos\ShopBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set brand
     *
     * @param \Lokos\ShopBundle\Entity\Brand $brand
     *
     * @return Product
     */
    public function setBrand(\Lokos\ShopBundle\Entity\Brand $brand = null)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get brand
     *
     * @return \Lokos\ShopBundle\Entity\Brand
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Add atribute
     *
     * @param \Lokos\ShopBundle\Entity\Atribute $atribute
     *
     * @return Product
     */
    public function addAtribute(\Lokos\ShopBundle\Entity\Atribute $atribute)
    {
        $this->atribute[] = $atribute;

        return $this;
    }

    /**
     * Remove atribute
     *
     * @param \Lokos\ShopBundle\Entity\Atribute $atribute
     */
    public function removeAtribute(\Lokos\ShopBundle\Entity\Atribute $atribute)
    {
        $this->atribute->removeElement($atribute);
    }

    /**
     * Get atribute
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAtribute()
    {
        return $this->atribute;
    }

    /**
     * Add option
     *
     * @param \Lokos\ShopBundle\Entity\Option $option
     *
     * @return Product
     */
    public function addOption(\Lokos\ShopBundle\Entity\Option $option)
    {
        $this->option[] = $option;

        return $this;
    }

    /**
     * Remove option
     *
     * @param \Lokos\ShopBundle\Entity\Option $option
     */
    public function removeOption(\Lokos\ShopBundle\Entity\Option $option)
    {
        $this->option->removeElement($option);
    }

    /**
     * Get option
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOption()
    {
        return $this->option;
    }

    /**
     * Add order
     *
     * @param \Lokos\ShopBundle\Entity\Order $order
     *
     * @return Product
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
}
