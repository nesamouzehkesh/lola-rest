<?php

namespace ProductBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\FOSRestController;
use ProductBundle\Entity\Brand;

class BrandController extends FOSRestController
{
    /**
    * @ApiDoc()
    * 
    * @Get("/brands", name="api_admin_get_brands", options={ "method_prefix" = false })
    */
    public function getBrandsAction(Request $request)
    {
        $id = $request->query->get('id', null);
        
        $brands = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('ProductBundle:Brand')
            ->getBrands();
        
        return $brands;
    }
    
    /**
     * @ApiDoc()
     * 
     * @Get("/brand/{id}", defaults={"id": null}, name="api_admin_get_brand", options={ "method_prefix" = false })
    */
    public function getBrandAction($id)
    {
        $brand = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('ProductBundle:Brand')
            ->getBrand($id);
        
        return $brand;
    }
    
}


