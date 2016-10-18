<?php

namespace CustomerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\FOSRestController;
use CustomerBundle\Entity\Order;
use CustomerBundle\Entity\Address;

class OrderController extends FOSRestController
{
    /**
    * @ApiDoc()
    * 
    * @Get("/orders", name="api_admin_get_orders", options={ "method_prefix" = false })
    */
    public function getOrdersAction(Request $request)
    {
        /*get all existing orders*/
        $id = $request->query->get('id', null);
        
        $orders = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('CustomerBundle:Order')
            ->getOrders($id);
        
        return $orders;
    }
     
    /**
     * @ApiDoc()
     * 
     * @Get("/order/{id}", defaults={"id": null}, name="api_admin_get_order", options={ "method_prefix" = false })
    */
    public function getOrderAction($id)
    {
        $order = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('CustomerBundle:Order')
            ->getOrder($id);
        
        return $order;
    }
    
    /**
    * @ApiDoc()
    * 
    * @Get("/customer-orders/{id}", name="api_admin_get_customer-orders", options={ "method_prefix" = false })
    */
    public function getCustomerOrdersAction($id)
    {
        $orders = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('CustomerBundle:Order')
            ->getCustomerOrders($id);
        
        return $orders;
    }
    
    /**
     * 
     * @ApiDoc()
     * 
     * @Delete("/order/{id}", name="api_admin_delete_order", options={ "method_prefix" = false })
     */ 
    public function deleteOrderAction($id)
    {
        // Get the order from order service. 
        $order = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('CustomerBundle:Order')
            ->find($id);

        // Use deleteEntity function in app.service to delete this entity        
        $this->get('app.service')->deleteEntity($order);
        
        // There is a debate if this should be a 404 or a 204
        // see http://leedavis81.github.io/is-a-http-delete-requests-idempotent/
        return $this->routeRedirectView(
            'api_admin_get_orders', 
            array(), 
            Response::HTTP_NO_CONTENT
            );        
    } 

    /**
     * @ApiDoc()
     * 
     * @Post("/postorder", name="api_admin_order_postorder", options={ "method_prefix" = false })
     */ 
    public function submitOrderAction(Request $request)
    {
        //make the order object in this action, compelete it with everything and 
        //return it to the frontend 
        $param = $request->request->all();
        var_dump($param);
        
        $addressService = $this->get('address.service');
        
        $em = $this->getDoctrine()->getManager();
        $customer = $this->get('customer.service')->getCustomer();
        
        if (isset($param['shipping'])) { //if there are already enterred addresses
            if ($param['setNewShipping']) { //if user wants to again enter another address for sh
               $shipping = $addressService->makeAddress($customer, $param['newShipping'], 
                    Address::TYPE_SHIPPING, $param[' setNewShippingAsPrimary']); 
                if ($param['sameAddress']) { // use this as billing too
                    $billing = $addressService->makeAddress($customer, $param['newShipping'],   
                       Address::TYPE_BILLING, $param['setNewBillingAsPrimary']); 
                } else { //if user does not want to use this sh as b too then:
                     if ($param['setNewBilling']) { //if it wants a new b address then:
                         $billing = $addressService->makeAddress($customer, $param['newBilling'],   
                            Address::TYPE_BILLING, $param['setNewBillingAsPrimary']); 
                     } else { //otherwise if it wants to keep the previous billing address:
                          $billing = $addressService->getAddress($customer, Address::TYPE_BILLING); 
                        }
                    }
                }
            else { //if user does not intend to add a new address for sh besides the previous one:
                $shipping = $addressService->getAddress($customer, Address::TYPE_SHIPPING);
                
                if ($param['setNewBilling']) { //if user wants a new billing address
                     $billing = $addressService->makeAddress($customer, $param['newBilling'],   
                        Address::TYPE_BILLING, $param['setNewBillingAsPrimary']); 
                }
                else { //if user does not intend to add a new address for b besides the previous one:
                   $billing = $addressService->getAddress($customer, Address::TYPE_BILLING); 
                }
            }
        // Else user has yet no addresses:
        } else { 
            $shipping = $addressService->makeAddress(
                $customer, 
                $param['newShipping'], 
                Address::TYPE_SHIPPING, 
                true // make sure it's primary
                );
            // Use it as billing too?
            if ($param['sameAddress']) {
                $billing = $addressService->makeAddress(
                    $customer, 
                    $param['newShipping'],   
                    Address::TYPE_BILLING, 
                    true
                    );   
            // if user wants a different new billing address:     
            } else {
                $billing = $addressService->makeAddress(
                    $customer, 
                    $param['newBilling'],
                    Address::TYPE_BILLING,
                    true // make sure it's primary
                    );
            }
        }
          
        $order = new Order;
        
        $order->setShippingAddress($shipping); //order.shippingAddress
        $order->setBillingAddress($billing);   //order.billingAddress
        
        $items = $this->getDoctrine()
            ->getManager()
            ->getRepository('CustomerBundle:Basket')
            ->getBasketItems($customer, false);
       
        $order->setCustomer($customer);
        foreach ($items as $item) {
            $orderDetail = new OrderDetail();
            $orderDetail->setQuantity($item->getQuantity()); 
            $orderDetail->setProduct($item->getProduct());   
            $orderDetail->setOrder($order);
            $order->addOrderDetail($orderDetail);

            $em->persist($orderDetail);
        }
        
        //we can now persist the order object
        $em->persist($order);
        $em->flush();
        
        //we now have to empty the basket items for this customer:
        foreach ($items as $item) {
            $this->get('app.service')->deleteEntity($item);
        }
        
        return array('id' => $order->getId()); //frontend only needs this order object's id       
    }
    
    /**
     * @ApiDoc()
     * 
     * @Get("/yourorder/{id}", defaults={"id": null}, name="api_admin_get_yourorder", options={ "method_prefix" = false })
    */
    public function getOrderDetailsAction($id)
    {
        $order = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('CustomerBundle:Order')
            ->getOrder($id);
        
        return $order;
    }
       
}
