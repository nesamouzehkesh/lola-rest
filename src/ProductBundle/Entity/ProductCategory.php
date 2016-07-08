<?php

namespace ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Library\Base\BaseEntity;

/**
 * ProductCategory
 *
 * @ORM\Table(name="lola_product_category")
 * @ORM\Entity(repositoryClass="ProductBundle\Repository\ProductCategoryRepository")
 */
class ProductCategory extends BaseEntity
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
    
     // ...
    /**
     * @ORM\OneToMany(targetEntity="ProductStock", mappedBy="productCategory")
     */
    private $productStocks;
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        
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
     * @return ProductCategory
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
     * Add productStock
     *
     * @param \ProductBundle\Entity\ProductStock $productStock
     *
     * @return ProductCategory
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
