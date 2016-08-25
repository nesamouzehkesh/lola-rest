<?php

namespace ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @var int
     *
     * @ORM\Column(name="brandId", type="integer", nullable=true)
     */
    private $brandId;

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
     * @ORM\Column(name="features", type="text", nullable=true)
     */
    private $features;
    
     /**
     * @var string
     *
     * @ORM\Column(name="originalPrice", type="string", length=255, nullable=true)
     */
    private $originalPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="string", length=255, nullable=true)
     */
    private $price;

    /**
     * @ORM\OneToMany(targetEntity="ProductCategory", mappedBy="product")
     */
    private $productCategories;
    
    /**
     * 
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->productCategories = new ArrayCollection();
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
     * Set brandId
     *
     * @param integer $brandId
     *
     * @return Product
     */
    public function setBrandId($brandId)
    {
        $this->brandId = $brandId;

        return $this;
    }

    /**
     * Get brandId
     *
     * @return integer
     */
    public function getBrandId()
    {
        return $this->brandId;
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
     * Set features
     *
     * @param string $features
     *
     * @return Product
     */
    public function setFeatures($features)
    {
        $this->features = $features;

        return $this;
    }

    /**
     * Get features
     *
     * @return string
     */
    public function getFeatures()
    {
        return $this->features;
    }

    /**
     * Set originalPrice
     *
     * @param string $originalPrice
     *
     * @return Product
     */
    public function setOriginalPrice($originalPrice)
    {
        $this->originalPrice = $originalPrice;

        return $this;
    }

    /**
     * Get originalPrice
     *
     * @return string
     */
    public function getOriginalPrice()
    {
        return $this->originalPrice;
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
     * Add productCategory
     *
     * @param \ProductBundle\Entity\ProductCategory $productCategory
     *
     * @return Product
     */
    public function addProductCategory(\ProductBundle\Entity\ProductCategory $productCategory)
    {
        $this->productCategories[] = $productCategory;

        return $this;
    }

    /**
     * Remove productCategory
     *
     * @param \ProductBundle\Entity\ProductCategory $productCategory
     */
    public function removeProductCategory(\ProductBundle\Entity\ProductCategory $productCategory)
    {
        $this->productCategories->removeElement($productCategory);
    }

    /**
     * Get productCategories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductCategories()
    {
        $productCategories = new ArrayCollection();
        foreach ($this->productCategories as $pCategory) {
            if (!$pCategory->isDeleted()) {
                $productCategories->add($pCategory);
            }
        }
        
        return $productCategories;
    }
}
