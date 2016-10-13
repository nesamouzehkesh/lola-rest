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
            ->getOrders();
        
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
     * @ApiDoc()
     * 
     * @Get("/details/{id}", name="api_admin_get_details", options={ "method_prefix" = false })
    */
    public function getOrderDetailsAction($id)
    {
        $orderDetails = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('CustomerBundle:Order')
            ->getOrderDetails($id);
        
        return $orderDetails;
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
        $param = $request->request->all();
        var_dump($param);
        exit;
        
        
        $em = $this->getDoctrine()->getManager();
        $customer = $this->get('customer.service')->getCustomer();
        $items = $this->getDoctrine()
            ->getManager()
            ->getRepository('CustomerBundle:Basket')
            ->getBasketItems($customer, false);

        $order = new Order;
        $order->setCustomer($customer);
        foreach ($items as $item) {
            $orderDetail = new OrderDetail();
            $orderDetail->setQuantity($item->getQuantity());
            $orderDetail->setProduct($item->getProduct());
            $orderDetail->setOrder($order);
            $order->addOrderDetail($orderDetail);
            

            
            $em->persist($orderDetail);
        }
        //now we have an order object with a specific customer and orderDetails
        // Persist $order
        $em->persist($order);
        $em->flush();
        
        //we now have to empty the basket items for this customer:
       // foreach ($items as $item) {
            //$this->get('app.service')->deleteEntity($item);
        //}
        
        return array();        
    }

   
}
