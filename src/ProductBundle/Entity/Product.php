<?php

namespace ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Library\Base\BaseEntity;

/**
 * Product
 *
 * @ORM\Table(name="lola_product")
 * @ORM\Entity(repositoryClass="ProductBundle\Repository\ProductRepository")
 */
class Product extends BaseEntity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="string", length=255)
     */
    private $price;

    /**
     * @ORM\OneToMany(targetEntity="ProductStock", mappedBy="product")
     */
    private $productStocks;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->productStocks = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param string $price
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
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Add productStock
     *
     * @param \ProductBundle\Entity\ProductStock $productStock
     *
     * @return Product
     */
    public function addProductStock(\ProductBundle\Entity\ProductStock $productStock)
    {
        $this->productStocks[] = $productStock;

        return $this;
    }

    /**
     * Remove productStock
     *
     * @param \ProductBundle\Entity\ProductStock $productStock
     */
    public function removeProductStock(\ProductBundle\Entity\ProductStock $productStock)
    {
        $this->productStocks->removeElement($productStock);
    }

    /**
     * Get productStocks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductStocks()
    {
        return $this->productStocks;
    }
}
