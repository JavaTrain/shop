<?php

namespace Lokos\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Option
 *
 * @ORM\Table(name="`option`")
 * @ORM\Entity(repositoryClass="Lokos\ShopBundle\Repositories\OptionRepository")
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

    /**
     * @ORM\OneToMany(targetEntity="OptionValue", mappedBy="option", cascade={"persist", "remove"})
     */
    private $optionValues;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->category     = new ArrayCollection();
        $this->optionValues = new ArrayCollection();
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
    public function getOptionValues()
    {
        return $this->optionValues;
    }

    /**
     * @param OptionValue $optionValue
     *
     * @return $this
     */
    public function addOptionValues(OptionValue $optionValue)
    {
        $this->optionValues[] = $optionValue;

        return $this;
    }

    /**
     * @param OptionValue $optionValue
     *
     * @return $this
     */
    public function removeOptionValue(OptionValue $optionValue)
    {
        $this->optionValues->removeElement($optionValue);

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
