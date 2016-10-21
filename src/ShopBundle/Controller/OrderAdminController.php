<?php

namespace ShopBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\FOSRestController;

class OrderAdminController extends FOSRestController
{
    /**
    * @ApiDoc()
    * 
    * @Get("/orders", name="api_admin_shop_get_orders", options={ "method_prefix" = false })
    */
    public function getOrdersAction(Request $request)
    {
        $orders = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('ShopBundle:Order')
            ->getOrders();
        
        return $orders;
    }
     
    /**
     * @ApiDoc()
     * 
     * @Get("/orders/{id}", defaults={"id": null}, name="api_admin_shop_get_order", options={ "method_prefix" = false })
    */
    public function getOrderAction($id)
    {
        $order = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('ShopBundle:Order')
            ->getOrder($id);
        
        return $order;
    }
    
    /**
     * @ApiDoc()
     * 
     * @Get("/orders/{id}/details", defaults={"id": null}, name="api_admin_shop_get_order_details", options={ "method_prefix" = false })
    */
    public function getOrderDetailsAction($id)
    {
        $order = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('ShopBundle:Order')
            ->getOrder($id);
        
        return $order;
    }    
    
    /**
    * @ApiDoc()
    * 
    * @Get("/user-orders/{id}", name="api_admin_shop_get_user_orders", options={ "method_prefix" = false })
    */
    public function getUserOrdersAction($id)
    {
        $orders = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('ShopBundle:Order')
            ->getUserOrders($id);
        
        return $orders;
    }
    
    /**
     * 
     * @ApiDoc()
     * 
     * @Delete("/orders/{id}", name="api_admin_shop_delete_order", options={ "method_prefix" = false })
     */ 
    public function deleteOrderAction($id)
    {
        // Get the order from order service. 
        $order = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('ShopBundle:Order')
            ->find($id);

        // Use deleteEntity function in app.service to delete this entity        
        $this->get('app.service')->deleteEntity($order);
        
        return $this->routeRedirectView(
            'api_admin_shop_get_orders', 
            array(), 
            Response::HTTP_NO_CONTENT
            );        
    } 
}
