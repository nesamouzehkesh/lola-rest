<?php

namespace ProductBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\FOSRestController;

class ProductController extends FOSRestController
{
    /**
     * @ApiDoc()
     * 
     * @Get("/products", name="api_admin_get_products", options={ "method_prefix" = false })
    */
    public function getProductsAction(Request $request)
    {
        $id = $request->query->get('id', null);
        
        $products = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('ProductBundle:ProductStock')
            ->getProducts($id);
        
        return $products;
    }

    /**
     * @ApiDoc()
     * 
     * @Get("/product/{id}", defaults={"id": null}, name="api_admin_get_product", options={ "method_prefix" = false })
    */
    public function getProductAction($id)
    {
        $product = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('ProductBundle:Product')
            ->find($id);
        
        return $product;
    }
    
     /**
     * 
     * @ApiDoc()
     * 
     * @Delete("/product/{id}", name="api_admin_delete_product", options={ "method_prefix" = false })
     */ 
    public function deleteProductAction($id)
    {
        // Get a page from page service. 
        $product = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('ProductBundle:Product')
            ->find($id);

        // Use deleteEntity function in app.service to delete this entity        
        $this->get('app.service')->deleteEntity($product);
        
        // There is a debate if this should be a 404 or a 204
        // see http://leedavis81.github.io/is-a-http-delete-requests-idempotent/
        return $this->routeRedirectView(
            'api_admin_get_products', 
            array(), 
            Response::HTTP_NO_CONTENT
            );        
    } 
}