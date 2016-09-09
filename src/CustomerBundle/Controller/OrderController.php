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
     * @Get("/orderDetails", name="api_admin_get_orderDetails", options={ "method_prefix" = false })
    */
    public function getOrderDetailsAction(Request $request)
    {
        
        $criteria = $request->query->all();
        
        $orderDetails = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('CustomerBundle:OrderDetail')
            ->getOrderDetails($criteria);
        
        return $orderDetails;
        
    }
    
}
