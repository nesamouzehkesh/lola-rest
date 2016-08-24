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

class ProductController extends FOSRestController
{
    /**
     * @ApiDoc()
     * 
     * @Get("/products", name="api_admin_get_products", options={ "method_prefix" = false })
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
     * @Get("/product/{id}", defaults={"id": null}, name="api_admin_get_product", options={ "method_prefix" = false })
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
    
    /**
     * @ApiDoc()
     * 
     * @Post("/product", name="api_admin_post_product", options={ "method_prefix" = false })
     */ 
    public function postProductAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        // Get front end data
        $data = $request->request->get('product');
        //Note: you can not use $request->query->get('product') since your data
        // // is sent to this api by POST method not GET
        //$data = $request->query->get('product');
        
        if (isset($data['id'])) {
            // Find a product for edit
            $product = $em->getRepository('ProductBundle:Product')->find($data['id']);
        } else {
            // Create a new Product object for add
            $product = new Product();
        }

        $product->setName($data['name']);
        $product->setPrice($data['price']);
        
        if (isset($data['description'])) {
            $product->setDescription($data['description']);
        }
        
        foreach ($data['category'] as $item) {
            $category = $em
                ->getRepository('ProductBundle:Category')
                ->find($item['id']);

            $productCategory = new ProductCategory();
            $productCategory->setProduct($product);
            $productCategory->setCategory($category);
            
            // Persist $productCategory
            $em->persist($productCategory);
        } 
        
        // Persist $product
        $em->persist($product);
        $em->flush();
        
        // You can expose whatever you want to your frontend here, such as 
        // productId in this case
        return array(
            'id' => $product->getId(),
            'name' => $product->getName()
            );
    }
}

