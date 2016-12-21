<?php

namespace Lokos\ShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Exclude;

/**
 * OptionValue
 *
 * @ORM\Table(name="option_value", indexes={@ORM\Index(name="fk_option_detail_option1_idx", columns={"option_id"})})
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
     * @Exclude
     * @var \Option
     *
     * @ORM\ManyToOne(targetEntity="Option", inversedBy="optionValues")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="option_id", referencedColumnName="id")
     * })
     */
    private $option;

    /**
     * @var int
     */
    private $price = 0;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->orderDetail = new ArrayCollection();
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
    public function setOption(Option $option = null)
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
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param $price
     *
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }



    /**
     * @return string
     */
    function __toString()
    {
        return $this->value;
    }
}
