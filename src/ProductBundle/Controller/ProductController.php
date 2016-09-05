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
        
        if (!isset($data['categories'])) { //because if user does not select any category, there will be an error
            $data['categories'] = array();
        }
        if (!isset($data['labels'])) { //because if user does not select any label, there will be an error
            $data['labels'] = array();
        }
        
        if (isset($data['id'])) { //if it is editing and not adding a new item
            // Find that product for editing
            $product = $em->getRepository('ProductBundle:Product')->find($data['id']);
            // that product has categories; Get the ids of the categories selected in dropdown
            $ids = array();
            foreach ($data['categories'] as $key => $category) {  //current categories (before and now)
                // we need the category index to be same as the index for ids
                // This $key will be used as $index
                $ids[$key] = $category['id'];
            }
            
            $lIds = array();
            foreach ($data['labels'] as $key => $label) {  //current labels (before and now)
                // we need the label index to be same as the index for lIds
                // This $key will be used as $index
                $lIds[$key] = $label['id'];
            } 
            
            
            foreach ($product->getProductCategories() as $pCategory) { //from ProductCategory table
                $categoryId = $pCategory->getCategory()->getId();
                if (in_array($categoryId, $ids)) {  
                    $index = array_search($categoryId, $ids); //returns the index of the searched-and-found element
                    // remove it from data category so we don't insert it again
                    unset($data['categories'][$index]); 
                } else {
                    $this->get('app.service')->deleteEntity($pCategory); //if a categpry number exists in the 
                    //ProductCategory table that is not among the $data[categories] (in the $ids array) then 
                    //you have to remove it from the ProductCategory table. Remember: $ids array is the only
                    //set of categories that a product should have...
                }
            }
            
            $labelRelations = $em->getRepository('LabelBundle:LabelRelation')->
                getEntityLabels($product->getId(),'product');
            foreach ($labelRelations as $labelR) { //by means of LabelRelation repository
             $labelId = $labelR->getId();
             if (in_array($labelId, $lIds)) {  
                 $index = array_search($labelId, $lIds); //returns the index of the searched-and-found element
                 // remove it from data category so we don't insert it again
                 unset($data['labels'][$index]); 
             } else {
                 $this->get('app.service')->deleteEntity($label); 
             }
         }
            
        } else {
            // Create a new Product object for add
            $product = new Product();
        }

        $product->setName($data['name']);
        $product->setPrice($data['price']);
        
        if (isset($data['description'])) {
            $product->setDescription($data['description']);
        }
        
        foreach ($data['categories'] as $item) {
            $category = $em
                ->getRepository('ProductBundle:Category')
                ->find($item['id']);

            $productCategory = new ProductCategory();
            $productCategory->setProduct($product);
            $productCategory->setCategory($category);
            
            $em->persist($productCategory);
        }
            
        foreach ($data['labels'] as $item) {
            $label = $em
                ->getRepository('LabelBundle:Label')
                ->find($item['id']);

            $labelRelation = new LabelRelation();
            $labelRelation->setlabel($label);
            $labelRelation->setEntityId($product->getId());
            $labelRelation->setEntityName('product');
            
            $em->persist($labelRelation);
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

