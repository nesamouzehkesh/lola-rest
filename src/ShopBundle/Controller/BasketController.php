<?php

namespace ShopBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\FOSRestController;
use ShopBundle\Entity\Basket;

class BasketController extends FOSRestController
{
    /**
    * @ApiDoc()
    * 
    * @Get("/items", name="api_shop_get_basket_items", options={ "method_prefix" = false })
    */
    public function getBasketItemsAction(Request $request)
    {
        $user = $this->get('user.service')->getUser();
        
        $items = $this->getDoctrine()
            ->getManager()
            ->getRepository('ShopBundle:Basket')
            ->getBasketItems($user);
        
        return $items;
    }
    
    /**
     * 
     * @ApiDoc()
     * 
     * @Delete("/items/{id}", name="api_shop_delete_basket_item", options={ "method_prefix" = false })
     */ 
    public function deleteBasketItemAction($id)
    {
        // Get the basket item from the BasketService 
        $item = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('ShopBundle:Basket')
            ->find($id);
        
        // Use deleteEntity function in app.service to delete this entity        
        $this->get('app.service')->deleteEntity($item);
        
        return $this->routeRedirectView(
            'api_shop_get_basket_items', 
            array(), 
            Response::HTTP_NO_CONTENT
            );   
    } 
    
    /**
     * @ApiDoc()
     * 
     * @Post("/items", name="api_shop_post_basket_item", options={ "method_prefix" = false })
     */ 
    public function postBasketItemAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        // Get front end data
        $params = $request->request->get('params');
        
        // Get current user doing thi action. We will later
        // get this user info from JSON web token in the request header
        $user = $this->get('user.service')->getUser();
        $product = $em->getRepository('ProductBundle:Product')->find($params['id']);
        
        $alreadyAdded = $em->getRepository('ShopBundle:Basket')->getBasketItem($user, $product);
        if ($alreadyAdded !== NULL) {
            return [];
        }
        
        $basketItem = new Basket();
        $basketItem->setUser($user);
        $basketItem->setProduct($product);
        $basketItem->setQuantity($params['count']);
        
        // Persist $product
        $em->persist($basketItem);
        $em->flush();
        
        return array('id' => $basketItem->getId());        
    }
    
    /**
    * @ApiDoc()
    * 
    * @Post("/items/{id}", name="api_shop_update_basket_item", options={ "method_prefix" = false })
    */ 
    public function updateBasketItemAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        // Get front end data
        $params = $request->request->get('params');
       
        $basket = $em->getRepository('ShopBundle:Basket')->find($id);
        $basket->setQuantity($params['quantity']);
        
        // Persist $basket
        $em->persist($basket);
        $em->flush();
        
        return array();        
    }
}
