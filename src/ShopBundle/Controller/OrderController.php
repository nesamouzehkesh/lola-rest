<?php

namespace ShopBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\FOSRestController;
use ShopBundle\Entity\Order;
use ShopBundle\Entity\OrderDetail;
use ShopBundle\Entity\Address;

class OrderController extends FOSRestController
{
    /**
     * @ApiDoc()
     * 
     * @Post("/orders", name="api_shop_post_order", options={ "method_prefix" = false })
     */ 
    public function submitOrderAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $param = $request->request->all();
        $addressService = $this->get('address.service');
        $user = $this->get('user.service')->getUser();
        
        // If there are already enterred addresses
        if (isset($param['shipping'])) { 
            // If user wants to again enter another address for sh
            if ($param['setNewShipping']) { 
                $shipping = $addressService->makeAddress(
                    $user, 
                    $param['newShipping'], 
                    Address::TYPE_SHIPPING, 
                    $param['setNewShippingAsPrimary']
                    ); 
                // Use this as billing too
                if ($param['sameAddress']) { 
                    $billing = $addressService->makeAddress(
                        $user, 
                        $param['newShipping'],   
                        Address::TYPE_BILLING, 
                        $param['setNewBillingAsPrimary']
                        ); 
                // If user does not want to use this sh as b too then:
                } else {
                    // If it wants a new b address then:
                    if ($param['setNewBilling']) { 
                        $billing = $addressService->makeAddress(
                            $user, 
                            $param['newBilling'],   
                            Address::TYPE_BILLING, 
                            $param['setNewBillingAsPrimary']
                            );
                    // Otherwise if it wants to keep the previous billing address:
                    } else { 
                        $billing = $addressService->getAddress(
                            $user, 
                            Address::TYPE_BILLING
                            ); 
                    }
                }
            // Else If user does not intend to add a new address for sh besides the previous one:    
            } else { 
                $shipping = $addressService->getAddress(
                    $user, 
                    Address::TYPE_SHIPPING
                    );
                // If user wants a new billing address
                if ($param['setNewBilling']) {
                    $billing = $addressService->makeAddress(
                        $user, 
                        $param['newBilling'],   
                        Address::TYPE_BILLING, 
                        $param['setNewBillingAsPrimary']
                        ); 
                // Else if user does not intend to add a new address for b besides the previous one:
                } else { 
                    $billing = $addressService->getAddress(
                        $user, 
                        Address::TYPE_BILLING
                        ); 
                }
            }
        // Else user has yet no addresses:
        } else { 
            $shipping = $addressService->makeAddress(
                $user, 
                $param['newShipping'], 
                Address::TYPE_SHIPPING, 
                true // make sure it's primary
                );
            // Use it as billing too?
            if ($param['sameAddress']) {
                $billing = $addressService->makeAddress(
                    $user, 
                    $param['newShipping'],   
                    Address::TYPE_BILLING, 
                    true
                    );   
            // If user wants a different new billing address:     
            } else {
                $billing = $addressService->makeAddress(
                    $user, 
                    $param['newBilling'],
                    Address::TYPE_BILLING,
                    true // make sure it's primary
                    );
            }
        }
        
        // Create a new Order Object
        $order = new Order;
        $order->setShippingAddress($shipping); //order.shippingAddress
        $order->setBillingAddress($billing);   //order.billingAddress
        $order->setUser($user);
        
        // Get all basket items
        $items = $this->getDoctrine()
            ->getManager()
            ->getRepository('ShopBundle:Basket')
            ->getBasketItems($user, false);
        // Add each basket item to order as an order details
        foreach ($items as $item) {
            $orderDetail = new OrderDetail();
            $orderDetail->setQuantity($item->getQuantity()); 
            $orderDetail->setProduct($item->getProduct());   
            $orderDetail->setOrder($order);
            $order->addOrderDetail($orderDetail);
            $em->persist($orderDetail);
        }
        
        //We can now persist the order object
        $em->persist($order);
        $em->flush();
        
        // We now have to empty the basket items for this user:
        foreach ($items as $item) {
            $this->get('app.service')->deleteEntity($item);
        }
        
        // Frontend only needs this order object's id  
        return array('id' => $order->getId());      
    }
}
