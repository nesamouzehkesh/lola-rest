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
    
    /**
     * "add_product"
     * [ADD] /products/{id}
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
     public function addProductAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        // Get front end data
        $data = $request->request->get('product');
        
        // Create a new Product object
        $product = new Product();
        $product->setName($data['name']);
        $product->setDescription($data['description']);
        $product->setPrice($data['price']);
       
        
        
        // Persist $product
        $em->persist($product);
        
        foreach ($data['products'] as $productData) {
            // Get a refrence to product entity. Note: that $product is not a 
            // Product object
            $product = $this->getDoctrine()
                ->getManager()
                ->getReference('ProductBundle:Product', $productData['id']);
            
            // Create a new ProductStock object (ProductStock table is the same as 
            //Products-Categories table in the database but we have created it by 
            //ourselves not letting Doctrine to create it for us during the 
            //many-to-many bidirectional table creation. 
            $productStock = new ProductStock();
            $productStock->setCount($productData['count']);
            $productStock->setColour($productData['colour']);
            
            
            // Add this $productStocks to $product
            $product->addProductStock($productStock);
            
            // Persist $productStock
            $em->persist($productStock);
        }
        $em->flush();
        
        //You can expose whatever you want to your frontend here, such as productId in this case
        return array(
            'id' => $product->getId()
            );
    }
}

