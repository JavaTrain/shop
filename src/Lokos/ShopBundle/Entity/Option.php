<?php

namespace Lokos\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Option
 *
 * @ORM\Table(name="`option`")
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
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=16777215, nullable=false)
     */
    private $description;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="option")
     * @ORM\JoinTable(name="option2category",
     *   joinColumns={
     *     @ORM\JoinColumn(name="option_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     *   }
     * )
     */
    private $category;

//    /**
//     * @var \Doctrine\Common\Collections\Collection
//     *
//     * @ORM\ManyToOne(targetEntity="Product2Option", inversedBy="product2Options")
//     */
//    private $product2Option;

    /**
     * @ORM\OneToMany(targetEntity="OptionValue", mappedBy="option")
     */
    private $optionValues;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->category     = new \Doctrine\Common\Collections\ArrayCollection();
        $this->optionValues = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Option
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
     * Add category
     *
     * @param \Lokos\ShopBundle\Entity\Category $category
     *
     * @return Option
     */
    public function addCategory(\Lokos\ShopBundle\Entity\Category $category)
    {
        $this->category[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \Lokos\ShopBundle\Entity\Category $category
     */
    public function removeCategory(\Lokos\ShopBundle\Entity\Category $category)
    {
        $this->category->removeElement($category);
    }

    /**
     * Get category
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Product2Option $product2Option
     *
     * @return $this
     */
    public function setProduct2Option(\Lokos\ShopBundle\Entity\Product2Option $product2Option)
    {
        $this->product2Option = $product2Option;

        return $this;
    }

//    /**
//     * Remove product
//     *
//     * @param \Lokos\ShopBundle\Entity\Product $product
//     */
//    public function removeProduct(\Lokos\ShopBundle\Entity\Product $product)
//    {
//        $this->product->removeElement($product);
//    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProduct2Option()
    {
        return $this->product2Option;
    }

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
     * @return string
     */
    function __toString()
    {
        return $this->getName();
    }


}
