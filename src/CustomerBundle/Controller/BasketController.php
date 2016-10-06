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
        
       // Get the basket item from the BasketService 
        $item = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('CustomerBundle:Basket')
            ->find($id);

        // Use deleteEntity function in app.service to delete this entity        
        $this->get('app.service')->deleteEntity($item);
        
        // There is a debate if this should be a 404 or a 204
        // see http://leedavis81.github.io/is-a-http-delete-requests-idempotent/
        return $this->routeRedirectView(
            'api_customer_get_basket_items', 
            array(), 
            Response::HTTP_NO_CONTENT
            );   
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
    
    /**
    * @ApiDoc()
    * 
    * @Post("/item", name="api_customer_update_basket_item", options={ "method_prefix" = false })
    */ 
    public function updateBasketItemAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        // Get front end data
        $params = $request->request->get('params');
        
        // Get current customer doing thi action. We will later
        // get this customer info from JSON web token in the request header
        $customer = $this->get('customer.service')->getCustomer();
        $basket = $em->getRepository('CustomerBundle:Basket')->find($params['id']);
        
        $basket->setCustomer($customer);
        $basket->setQuantity($params['quantity']);
        
        // Persist $basket
        $em->persist($basket);
        $em->flush();
        
        return array();        
    }
    
    
}
