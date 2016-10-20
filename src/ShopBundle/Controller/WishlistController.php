<?php

namespace ShopBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\FOSRestController;
use ShopBundle\Entity\Wishlist;

class WishlistController extends FOSRestController
{
    /**
    * @ApiDoc()
    * 
    * @Get("/items", name="api_shop_get_wishlist_items", options={ "method_prefix" = false })
    */
    public function getWishlistItemsAction(Request $request) 
    {
        $user = $this->get('user.service')->getUser();
        
        $items = $this->getDoctrine()
            ->getManager()
            ->getRepository('ShopBundle:Wishlist')
            ->getWishlistItems($user);
        
        return $items;
    }
    
    /**
     * @ApiDoc()
     * 
     * @Post("/items", name="api_shop_post_wishlist_item", options={ "method_prefix" = false })
     */ 
    public function postWishlistItemAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        // Get front end data
        $params = $request->request->get('params');
        
        // Get current user doing this action. We will later
        // get this user info from JSON web token in the request header
        $user = $this->get('user.service')->getUser();
        $product = $em->getRepository('ProductBundle:Product')->find($params['id']);
        
        // If item already exist do not create it
        $alreadyAddedItem = $em
            ->getRepository('ShopBundle:Wishlist')
            ->getWishlistItem($user, $product);
        if ($alreadyAddedItem !== NULL) {
            return array();
        }
        
        // Make a new Wishlist item
        $wishlistItem = new Wishlist();
        $wishlistItem->setUser($user);
        $wishlistItem->setProduct($product);
        
        // Persist $product
        $em->persist($wishlistItem);
        $em->flush();
        
        return array('id' => $wishlistItem->getId());        
    }
    
    /**
     * 
     * @ApiDoc()
     * 
     * @Delete("/items/{id}", name="api_shop_delete_wishlist_item", options={ "method_prefix" = false })
     */ 
    public function deleteWishlistItemAction($id)
    {
        $item = $this->getDoctrine()
            ->getManager()
            ->getRepository('ShopBundle:Wishlist')
            ->find($id);

        // Use deleteEntity function in app.service to delete this entity        
        $this->get('app.service')->deleteEntity($item);
        
        return $this->routeRedirectView(
            'api_shop_get_wishlist_items', 
            array(), 
            Response::HTTP_NO_CONTENT
            );  
    } 
}
