<?php

namespace ProductBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use ProductBundle\Entity\ProductCategory;


class CategoryController extends FOSRestController
{
    /**
    * @ApiDoc()
    * 
    * @Get("/categories", name="api_admin_get_categories", options={ "method_prefix" = false })
    */
    public function getCategoriesAction(Request $request)
    {
        $id = $request->query->get('id', null);
        
        $categories = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('ProductBundle:ProductCategory')
            ->getCategories();
        
        return $categories;
    }
}
