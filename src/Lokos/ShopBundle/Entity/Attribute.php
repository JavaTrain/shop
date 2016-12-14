<?php

namespace Lokos\ShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Attribute
 *
 * @ORM\Table(name="attribute")
 * @ORM\Entity(repositoryClass="Lokos\ShopBundle\Repositories\AttributeRepository")
 */
class Attribute
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
     * @ORM\ManyToMany(targetEntity="Category", mappedBy="attributes")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="AttributeValue", mappedBy="attribute", cascade={"persist", "remove"})
     */
    private $attributeValues;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->category        = new ArrayCollection();
        $this->attributeValues = new ArrayCollection();
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
     * @return Attribute
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
     * @return Attribute
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
     * @return Attribute
     */
    public function addCategory(Category $category)
    {
        $this->category[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \Lokos\ShopBundle\Entity\Category $category
     */
    public function removeCategory(Category $category)
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
     * @return mixed
     */
    public function getAttributeValues()
    {
        return $this->attributeValues;
    }

    /**
     * @param AttributeValue $attributeValue
     *
     * @return $this
     */
    public function addAttributeValues(AttributeValue $attributeValue)
    {
        $this->attributeValues[] = $attributeValue;

        return $this;
    }

    /**
     * @param AttributeValue $attributeValue
     *
     * @return $this
     */
    public function removeAttributeValue(AttributeValue $attributeValue)
    {
        $this->attributeValues->removeElement($attributeValue);

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
