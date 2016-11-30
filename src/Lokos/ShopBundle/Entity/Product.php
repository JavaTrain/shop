<?php

namespace Lokos\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="product", indexes={@ORM\Index(name="fk_product_category_idx", columns={"category_id"}), @ORM\Index(name="fk_product_brand1_idx", columns={"brand_id"})})
 * @ORM\Entity(repositoryClass="Lokos\ShopBundle\Repositories\ProductRepository")
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
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer", nullable=true)
     */
    private $quantity;

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
     * @ORM\ManyToMany(targetEntity="Attribute", mappedBy="product")
     */
    private $attribute;

//    /**
//     * @var \Doctrine\Common\Collections\Collection
//     *
//     * @ORM\OneToMany(targetEntity="Product2Option", mappedBy="product")
//     */
//    private $product2options;

    /**
     * @ORM\OneToMany(targetEntity="OptionValue", mappedBy="product")
     */
    private $optionValues;

    /**
     * @ORM\OneToMany(targetEntity="ProductSet", mappedBy="product", cascade={"persist", "remove", "detach"})
     */
    private $productSets;

    private $cartPrice;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->attribute       = new \Doctrine\Common\Collections\ArrayCollection();
//        $this->product2options = new \Doctrine\Common\Collections\ArrayCollection();
        $this->optionValues    = new \Doctrine\Common\Collections\ArrayCollection();
        $this->productSets     = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Product
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
     * Add attribute
     *
     * @param \Lokos\ShopBundle\Entity\Attribute $attribute
     *
     * @return Product
     */
    public function addAttribute(\Lokos\ShopBundle\Entity\Attribute $attribute)
    {
        $this->attribute[] = $attribute;

        return $this;
    }

    /**
     * Remove attribute
     *
     * @param \Lokos\ShopBundle\Entity\Attribute $attribute
     */
    public function removeAttribute(\Lokos\ShopBundle\Entity\Attribute $attribute)
    {
        $this->attribute->removeElement($attribute);
    }

    /**
     * Get attribute
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

//    /**
//     * Add option
//     *
//     * @param \Lokos\ShopBundle\Entity\Option $option
//     *
//     * @return Product
//     */
//    public function addProduct2options(\Lokos\ShopBundle\Entity\Option $option)
//    {
//        $this->product2options[] = $option;
//
//        return $this;
//    }
//
//    /**
//     * Remove option
//     *
//     * @param \Lokos\ShopBundle\Entity\Option $option
//     */
//    public function removeProduct2options(\Lokos\ShopBundle\Entity\Option $option)
//    {
//        $this->product2options->removeElement($option);
//    }
//
//    /**
//     * Get option
//     *
//     * @return \Doctrine\Common\Collections\Collection
//     */
//    public function getProduct2options()
//    {
//        return $this->product2options;
//    }

    /**
     * @return mixed
     */
    public function getOptionValues()
    {
        return $this->optionValues;
    }

    /**
     * @param $optionValues
     *
     * @return $this
     */
    public function setOptionValues($optionValues)
    {
        $this->optionValues = $optionValues;
        
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCartPrice()
    {
        return $this->cartPrice;
    }

    /**
     * @param $cartPrice
     *
     * @return $this
     */
    public function setCartPrice($cartPrice)
    {
        $this->cartPrice = $cartPrice;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductSets()
    {
        return $this->productSets;
    }

    /**
     * @param $productSets
     *
     * @return $this
     */
    public function setProductSets($productSets)
    {
        $this->productSets = $productSets;

        return $this;
    }

//    /**
//     * @param ProductSet $productSet
//     *
//     * @return $this
//     */
//    public function addProductSets(\Lokos\ShopBundle\Entity\ProductSet $productSet)
//    {
//        $this->productSets[] = $productSet;
//
//        return $this;
//    }
//
//    /**
//     * @param ProductSet $productSet
//     */
//    public function removeProductSet(\Lokos\ShopBundle\Entity\ProductSet $productSet)
//    {
//        $this->productSets->removeElement($productSet);
//    }

    /**
     * @return mixed
     */
    function __toString()
    {
        return ($this->getName())?$this->getName():'New product';
    }


}
