<?php

namespace CustomerBundle\Entity;
use AppBundle\Library\Base\BaseEntity;

use Doctrine\ORM\Mapping as ORM;

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
     * @var string
     *
     * @ORM\Column(name="orderDate", type="string", length=255)
     */
    private $orderDate;
    
    /**
    * @ORM\ManyToOne(targetEntity="Address")
    * @ORM\JoinColumn(name="shipping_address_id", referencedColumnName="id")
    */
    private $shippingAddress;

    /**
    * @ORM\ManyToOne(targetEntity="Address")
    * @ORM\JoinColumn(name="billing_address_id", referencedColumnName="id")
    */
    private $billingAddress;

    /**
     * @var bool
     *
     * @ORM\Column(name="giftWrap", type="boolean")
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
     * Set orderDate
     *
     * @param string $orderDate
     *
     * @return Orderr
     */
    public function setOrderDate($orderDate)
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    /**
     * Get orderDate
     *
     * @return string
     */
    public function getOrderDate()
    {
        return $this->orderDate;
    }

    /**
     * Set shippingAddress
     *
     * @param string $shippingAddress
     *
     * @return Orderr
     */
    public function setShippingAddress($shippingAddress)
    {
        $this->shippingAddress = $shippingAddress;

        return $this;
    }

    /**
     * Get shippingAddress
     *
     * @return string
     */
    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    /**
     * Set billingAddress
     *
     * @param string $billingAddress
     *
     * @return Orderr
     */
    public function setBillingAddress($billingAddress)
    {
        $this->billingAddress = $billingAddress;

        return $this;
    }

    /**
     * Get billingAddress
     *
     * @return string
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * Set giftWrap
     *
     * @param boolean $giftWrap
     *
     * @return Orderr
     */
    public function setGiftWrap($giftWrap)
    {
        $this->giftWrap = $giftWrap;

        return $this;
    }

    /**
     * Get giftWrap
     *
     * @return bool
     */
    public function getGiftWrap()
    {
        return $this->giftWrap;
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
