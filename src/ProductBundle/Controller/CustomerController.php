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
use ProductBundle\Entity\Category;

class CustomerController extends FOSRestController
{
    /**
     * @ApiDoc()
     * 
     * @Get("/products", name="api_customer_get_products", options={ "method_prefix" = false })
    */
    public function getProductsAction(Request $request)
    {
        //serach based on criteria (all query parameters)
        $criteria = $request->query->all();
        
        $products = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('ProductBundle:ProductCategory')
            ->getCategoryProducts($criteria);
        
        return $products;
        
    }
     
}

