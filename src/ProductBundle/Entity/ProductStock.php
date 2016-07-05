<?php

namespace ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Library\Base\BaseEntity;

/**
 * ProductStock
 *
 * @ORM\Table(name="lola_product_stock")
 * @ORM\Entity(repositoryClass="ProductBundle\Repository\ProductStockRepository")
 */
class ProductStock extends BaseEntity
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
     * @ORM\Column(name="count", type="string", length=255)
     */
    private $count;

    /**
     * @var string
     *
     * @ORM\Column(name="colour", type="string", length=255)
     */
    private $colour;
    
    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="productStocks")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;
    
    /**
     * @ORM\ManyToOne(targetEntity="ProductCategory", inversedBy="productStocks")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $productCategory;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set count
     *
     * @param string $count
     *
     * @return ProductStock
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return string
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set colour
     *
     * @param string $colour
     *
     * @return ProductStock
     */
    public function setColour($colour)
    {
        $this->colour = $colour;

        return $this;
    }

    /**
     * Get colour
     *
     * @return string
     */
    public function getColour()
    {
        return $this->colour;
    }

    /**
     * Set product
     *
     * @param \ProductBundle\Entity\Product $product
     *
     * @return ProductStock
     */
    public function setProduct(\ProductBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \ProductBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set productCategory
     *
     * @param \ProductBundle\Entity\ProductCategory $productCategory
     *
     * @return ProductStock
     */
    public function setProductCategory(\ProductBundle\Entity\ProductCategory $productCategory = null)
    {
        $this->productCategory = $productCategory;

        return $this;
    }

    /**
     * Get productCategory
     *
     * @return \ProductBundle\Entity\ProductCategory
     */
    public function getProductCategory()
    {
        return $this->productCategory;
    }
}
