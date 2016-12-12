<?php

namespace Lokos\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

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
     *
     * @Assert\NotBlank()
     *
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @Assert\NotBlank()
     *
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
     * @Assert\NotBlank()
     *
     * @var \Category
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="products")
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

    /**
     * @ORM\OneToMany(targetEntity="ProductSet", mappedBy="product", cascade={"persist"})
     */
    private $productSets;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->attribute   = new ArrayCollection();
        $this->productSets = new ArrayCollection();
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
    public function setCategory(Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return \Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Brand|null $brand
     *
     * @return $this
     */
    public function setBrand(Brand $brand = null)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get brand
     *
     * @return \Lokos\ShopBundle\Entity\Brand
     */
    /**
     * @return \Brand
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
    public function addAttribute(Attribute $attribute)
    {
        $this->attribute[] = $attribute;

        return $this;
    }

    /**
     * Remove attribute
     *
     * @param \Lokos\ShopBundle\Entity\Attribute $attribute
     */
    public function removeAttribute(Attribute $attribute)
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

    /**
     * @return mixed
     */
    function __toString()
    {
        return ($this->getName())?$this->getName():'New product';
    }


}
