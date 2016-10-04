<?php

namespace CustomerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\FOSRestController;
use CustomerBundle\Entity\Basket;

class BasketController extends FOSRestController
{
    /**
    * @ApiDoc()
    * 
    * @Get("/items", name="api_customer_get_basket_items", options={ "method_prefix" = false })
    */
    public function getBasketItemsAction(Request $request)
    {
        $customer = $this->get('customer.service')->getCustomer();
        
        $items = $this->getDoctrine()
            ->getManager()
            ->getRepository('CustomerBundle:Basket')
            ->getBasketItems($customer);
        
        return $items;
    }
    
    /**
     * 
     * @ApiDoc()
     * 
     * @Delete("/items/{id}", name="api_customer_delete_basket_item", options={ "method_prefix" = false })
     */ 
    public function deleteBasketItemAction($id)
    {
        
        return [];
    } 
    
    /**
     * @ApiDoc()
     * 
     * @Post("/items", name="api_customer_post_basket_items", options={ "method_prefix" = false })
     */ 
    public function postBasketItemAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        // Get front end data
        $params = $request->request->get('params');
        
        // Get current customer doing thi action. We will later
        // get this customer info from JSON web token in the request header
        $customer = $this->get('customer.service')->getCustomer();
        $product = $em->getRepository('ProductBundle:Product')->find($params['id']);
        
        $alreadyAdded = $em->getRepository('CustomerBundle:Basket')->getBasketItem($customer, $product);
        if ($alreadyAdded !== NULL) {
            return [];
        }
        
        $basketItem = new Basket();
        $basketItem->setCustomer($customer);
        $basketItem->setProduct($product);
        $basketItem->setQuantity($params['count']);
        
        // Persist $product
        $em->persist($basketItem);
        $em->flush();
        
        return array();        
    }
}
