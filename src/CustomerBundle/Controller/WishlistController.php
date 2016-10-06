<?php

namespace CustomerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\FOSRestController;
use CustomerBundle\Entity\Wishlist;

class WishlistController extends FOSRestController
{
    /**
    * @ApiDoc()
    * 
    * @Get("/items", name="api_customer_get_wishlist_items", options={ "method_prefix" = false })
    */
    public function getWishlistItemsAction(Request $request) 
    {
        $customer = $this->get('customer.service')->getCustomer();
        
        $items = $this->getDoctrine()
            ->getManager()
            ->getRepository('CustomerBundle:Wishlist')
            ->getWishlistItems($customer);
        
        return $items;
    }
    
    /**
     * @ApiDoc()
     * 
     * @Post("/items", name="api_customer_post_wishlist_items", options={ "method_prefix" = false })
     */ 
    public function postWishlistItemAction(Request $request) //the request parameter is product id
    {
        $em = $this->getDoctrine()->getManager();
        // Get front end data
        $params = $request->request->get('params');
        
        // Get current customer doing this action. We will later
        // get this customer info from JSON web token in the request header
        $customer = $this->get('customer.service')->getCustomer();
        $product = $em->getRepository('ProductBundle:Product')->find($params['id']);
        
        $alreadyAdded = $em
            ->getRepository('CustomerBundle:Wishlist')
            ->getWishlistItem($customer, $product);
        if ($alreadyAdded !== NULL) {
            return array();
        }
       
        $wishlistItem = new Wishlist();
        $wishlistItem->setCustomer($customer);
        $wishlistItem->setProduct($product);
        
        // Persist $product
        $em->persist($wishlistItem);
        $em->flush();
        
        
        return array();        
    }
    /**
     * 
     * @ApiDoc()
     * 
     * @Delete("/items/{id}", name="api_customer_delete_wishlist_item", options={ "method_prefix" = false })
     */ 
    public function deleteWishlistItemAction($id)
    {
        
        return [];
    } 
    
    
}
