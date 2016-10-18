<?php

namespace CustomerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Library\Base\BaseEntity;

/**
 * Orderr
 *
 * @ORM\Table(name="lola_order")
 * @ORM\Entity(repositoryClass="CustomerBundle\Repository\OrderRepository")
 */
class Order extends BaseEntity
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
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;
    
    /**
    * @ORM\ManyToOne(targetEntity="Address")
    * @ORM\JoinColumn(name="shipping_address_id", referencedColumnName="id", nullable=true)
    */
    private $shippingAddress;

    /**
    * @ORM\ManyToOne(targetEntity="Address")
    * @ORM\JoinColumn(name="billing_address_id", referencedColumnName="id", nullable=true)
    */
    private $billingAddress;

    /**
     * @var bool
     *
     * @ORM\Column(name="giftWrap", type="boolean", nullable=true)
     */
    private $giftWrap;
    
    /**
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="orders")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    private $customer;
    
    /**
    * @ORM\OneToMany(targetEntity="OrderDetail", mappedBy="order")
    */
    private $orderDetails;
    
    public function __construct() 
    {
        parent::__construct();
        
        $this->status = 0;
        $this->orderDetails = new ArrayCollection();
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
     * Set status
     *
     * @param integer $status
     *
     * @return Order
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set giftWrap
     *
     * @param boolean $giftWrap
     *
     * @return Order
     */
    public function setGiftWrap($giftWrap)
    {
        $this->giftWrap = $giftWrap;

        return $this;
    }

    /**
     * Get giftWrap
     *
     * @return boolean
     */
    public function getGiftWrap()
    {
        return $this->giftWrap;
    }

    /**
     * Set createdTime
     *
     * @param integer $createdTime
     *
     * @return Order
     */
    public function setCreatedTime($createdTime)
    {
        $this->createdTime = $createdTime;

        return $this;
    }

    /**
     * Set shippingAddress
     *
     * @param \CustomerBundle\Entity\Address $shippingAddress
     *
     * @return Order
     */
    public function setShippingAddress(\CustomerBundle\Entity\Address $shippingAddress = null)
    {
        $this->shippingAddress = $shippingAddress;

        return $this;
    }

    /**
     * Get shippingAddress
     *
     * @return \CustomerBundle\Entity\Address
     */
    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    /**
     * Set billingAddress
     *
     * @param \CustomerBundle\Entity\Address $billingAddress
     *
     * @return Order
     */
    public function setBillingAddress(\CustomerBundle\Entity\Address $billingAddress = null)
    {
        $this->billingAddress = $billingAddress;

        return $this;
    }

    /**
     * Get billingAddress
     *
     * @return \CustomerBundle\Entity\Address
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * Set customer
     *
     * @param \CustomerBundle\Entity\Customer $customer
     *
     * @return Order
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
     * Add orderDetail
     *
     * @param \CustomerBundle\Entity\OrderDetail $orderDetail
     *
     * @return Order
     */
    public function addOrderDetail(\CustomerBundle\Entity\OrderDetail $orderDetail)
    {
        $this->orderDetails[] = $orderDetail;

        return $this;
    }

    /**
     * Remove orderDetail
     *
     * @param \CustomerBundle\Entity\OrderDetail $orderDetail
     */
    public function removeOrderDetail(\CustomerBundle\Entity\OrderDetail $orderDetail)
    {
        $this->orderDetails->removeElement($orderDetail);
    }

    /**
     * Get orderDetails
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrderDetails()
    {
        return $this->orderDetails;
    }
}
