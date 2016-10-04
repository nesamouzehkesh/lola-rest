<?php

namespace CustomerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Library\Base\BaseEntity;

/**
 * Basket
 *
 * @ORM\Table(name="lola_basket")
 * @ORM\Entity(repositoryClass="CustomerBundle\Repository\BasketRepository")
 */
class Basket extends BaseEntity
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
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

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
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Basket
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set customer
     *
     * @param \CustomerBundle\Entity\Customer $customer
     *
     * @return Basket
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
     * @return Basket
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
