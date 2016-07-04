<?php

namespace Lokos\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Order
 *
 * @ORM\Table(name="`product2order`")
 * @ORM\Entity(repositoryClass="Lokos\ShopBundle\Repositories\ProductRepository")
 */
class Product2Order
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
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumn(name="id", referencedColumnName="id")
     */
    private $product;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Order")
     * @ORM\JoinColumn(name="id", referencedColumnName="id")
     */
    private $order;

    /**
     * @var string
     *
     * @ORM\Column(name="quontity", type="integer", nullable=false)
     */
    private $quontity;

    /**
     * Constructor
     */
    public function __construct()
    {
//        $this->option = new \Doctrine\Common\Collections\ArrayCollection();
//        $this->product = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return string
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
     * @return string
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param $order
     *
     * @return $this
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return string
     */
    public function getQuontity()
    {
        return $this->quontity;
    }

    /**
     * @param $quontity
     *
     * @return $this
     */
    public function setQuontity($quontity)
    {
        $this->quontity = $quontity;
        
        return $this;
    }

    
   
}
