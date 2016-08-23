<?php

namespace ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Library\Base\BaseEntity;

/**
 * Category
 *
 * @ORM\Table(name="lola_category")
 * @ORM\Entity(repositoryClass="ProductBundle\Repository\CategoryRepository")
 */
class Category extends BaseEntity
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
     * @ORM\OneToMany(targetEntity="ProductCategory", mappedBy="category")
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
     * Set name
     *
     * @param string $name
     *
     * @return Category
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
     * Add productCategory
     *
     * @param \ProductBundle\Entity\ProductCategory $productCategory
     *
     * @return Category
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
        return $this->productCategories;
    }
}