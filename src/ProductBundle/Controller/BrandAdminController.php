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

class BrandAdminController extends FOSRestController
{
    /**
    * @ApiDoc()
    * 
    * @Get("/brands", name="api_admin_product_get_brands", options={ "method_prefix" = false })
    */
    public function getBrandsAction(Request $request)
    {
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
     * @Get("/brands/{id}", defaults={"id": null}, name="api_admin_product_get_brand", options={ "method_prefix" = false })
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
    * @Post("/brands", name="api_admin_product_post_brand", options={ "method_prefix" = false })
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
        
        //You can expose whatever you want to your frontend here, such as brandId in this case
        return array(
            'name' => $brand->getName(),
            'description' => $brand->getDescription()
            );
    }
    
     /**
     * 
     * @ApiDoc()
     * 
     * @Delete("/brands/{id}", name="api_admin_product_delete_brand", options={ "method_prefix" = false })
     */ 
    public function deleteBrandAction($id)
    {
        // Get a brand from brand service. 
        $brand = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('ProductBundle:Brand')
            ->find($id);

        // Use deleteEntity function in app.service to delete this entity        
        $this->get('app.service')->deleteEntity($brand);
        
        // There is a debate if this should be a 404 or a 204
        // see http://leedavis81.github.io/is-a-http-delete-requests-idempotent/
        return $this->routeRedirectView(
            'api_admin_product_brand_get_brands', 
            array(), 
            Response::HTTP_NO_CONTENT
            );        
    } 
}


