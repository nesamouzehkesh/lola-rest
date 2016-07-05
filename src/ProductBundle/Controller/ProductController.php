<?php

namespace ProductBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\FOSRestController;

class ProductController extends FOSRestController
{
    /**
     * @ApiDoc()
     * 
     * @Get("/products", name="api_admin_get_products")
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
     * @Get("/product/{id}", defaults={"id": null}, name="api_admin_get_product")
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
     * "delete_product"
     * [DELETE] /products/{id}
     * 
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     403 = "Returned when the product is not authorized to say hello",
     *     404 = {
     *       "Returned when the product is not found",
     *       "Returned when something else is not found"
     *     }
     *   }
     * )
     * @Annotations\View()
     * 
     * @Annotations\QueryParam(name="id", requirements="\d+", nullable=true, description="Product ID")
     * @return array
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

    } 
    
}
