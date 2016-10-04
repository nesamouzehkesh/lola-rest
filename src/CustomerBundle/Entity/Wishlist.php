<?php

namespace CustomerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Library\Base\BaseEntity;

/**
 * Wishlist
 *
 * @ORM\Table(name="lola_wishlist")
 * @ORM\Entity(repositoryClass="CustomerBundle\Repository\WishlistRepository")
 */
class Wishlist extends BaseEntity
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
    * @ORM\ManyToOne(targetEntity="Customer")
    * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
    */
    private $customer;
    
    /**
    * @ORM\ManyToOne(targetEntity="ProductBundle\Entity\Product")
    * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
    */
    private $product;

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
     * Set customer
     *
     * @param \CustomerBundle\Entity\Customer $customer
     *
     * @return Wishlist
     */
    public function setCustomer(\CustomerBundle\Entity\Customer $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \CustomerBundle\Entity\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set product
     *
     * @param \ProductBundle\Entity\Product $product
     *
     * @return Wishlist
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
}
