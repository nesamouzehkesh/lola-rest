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
    
    /**
    * @ApiDoc()
    * 
    * @Post("/brand", name="api_admin_post_brand", options={ "method_prefix" = false })
    */ 
    public function postBrandAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        // Get front end data
        $data = $request->request->get('brand');
       
        if (isset($data['id'])) {
            // Find a brand for edit
            $brand = $em->getRepository('ProductBundle:Brand')->find($data['id']);
        } else {
            // Create a new Brand object for add
            $brand = new Brand();
        }
        
        $brand->setName($data['name']);
        $brand->setDescription($data['description']);
        
        // Persist $brand
        $em->persist($brand);
        
        $em->flush();
        
        //You can expose whatever you want to your frontend here, such as themeId in this case
        return array(
            'name' => $brand->getName(),
            'description' => $brand->getDescription()
            );
    }
    
}


