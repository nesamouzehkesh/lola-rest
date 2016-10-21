<?php

namespace ProductBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\FOSRestController;

class CategoryController extends FOSRestController
{
    /**
    * @ApiDoc()
    * 
    * @Get("/categories", name="api_shop_product_get_categories", options={ "method_prefix" = false })
    */
    public function getCategoriesAction(Request $request)
    {
        $categories = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('ProductBundle:Category')
            ->getCategories();
        
        return $categories;
    }
    
    /**
     * @ApiDoc()
     * 
     * @Get("/categories/{id}", defaults={"id": null}, name="api_shop_product_get_category", options={ "method_prefix" = false })
    */
    public function getCategoryAction($id)
    {
        $category = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('ProductBundle:Category')
            ->getCategory($id);
        
        return $category;
    }
}


