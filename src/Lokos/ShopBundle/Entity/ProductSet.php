<?php

namespace Lokos\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="product_set")
 * @ORM\Entity(repositoryClass="Lokos\ShopBundle\Repositories\ProductSetRepository")
 */
class ProductSet
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
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;

    /**
     * @var \Product
     *
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="productSets")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * })
     */
    private $product;

    /**
     * @ORM\OneToMany(targetEntity="Product2Option", mappedBy="productSet")
     */
    private $product2Options;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->product2Options = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param $name
     *
     * @return $this
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
     * @return mixed
     */
    public function getProduct2Options()
    {
        return $this->product2Options;
    }

    /**
     * @param $product2Options
     *
     * @return $this
     */
    public function setProduct2Options($product2Options)
    {
        $this->product2Options = $product2Options;

        return $this;
    }

    /**
     * @return string
     */
    function __toString()
    {
        return $this->getName();
    }


}
