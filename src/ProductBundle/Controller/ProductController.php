<?php

namespace ProductBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\FOSRestController;
use ProductBundle\Entity\Product;
use ProductBundle\Entity\ProductCategory;
use LabelBundle\Entity\LabelRelation;

class ProductController extends FOSRestController
{
    /**
     * @ApiDoc()
     * 
     * @Get("/products", name="api_shop_product_get_products", options={ "method_prefix" = false })
    */
    public function getProductsAction(Request $request)
    {
        // Search criteria
        // Get all query parameters
        $criteria = $request->query->all();
        
        $products = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('ProductBundle:Product')
            ->getProducts($criteria);
        
        return $products;
    }

    /**
     * @ApiDoc()
     * 
     * @Get("/products/{id}", defaults={"id": null}, name="api_shop_product_get_product", options={ "method_prefix" = false })
    */
    public function getProductAction($id)
    {
        $product = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('ProductBundle:Product')
            ->getProduct($id);
        
        return $product;
    }
}