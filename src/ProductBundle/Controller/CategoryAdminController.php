<?php

namespace ProductBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\FOSRestController;
use ProductBundle\Entity\Category;

class CategoryAdminController extends FOSRestController
{
    /**
    * @ApiDoc()
    * 
    * @Get("/categories", name="api_admin_product_get_categories", options={ "method_prefix" = false })
    */
    public function getCategoriesAction(Request $request)
    {
        $id = $request->query->get('id', null);
        
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
     * @Get("/category/{id}", defaults={"id": null}, name="api_admin_product_get_category", options={ "method_prefix" = false })
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
    
    /**
     * 
     * @ApiDoc()
     * 
     * @Delete("/category/{id}", name="api_admin_product_delete_category", options={ "method_prefix" = false })
     */ 
    public function deleteCategoryAction($id)
    {
        // Get a category from category service. 
        $category = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('ProductBundle:Category')
            ->find($id);

        // Use deleteEntity function in app.service to delete this entity        
        $this->get('app.service')->deleteEntity($category);
        
        // There is a debate if this should be a 404 or a 204
        // see http://leedavis81.github.io/is-a-http-delete-requests-idempotent/
        return $this->routeRedirectView(
            'api_admin_get_categories', 
            array(), 
            Response::HTTP_NO_CONTENT
            );        
    } 
    
    /**
     * @ApiDoc()
     * 
     * @Post("/category", name="api_admin_post_category", options={ "method_prefix" = false })
     */ 
    public function postCategoryAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        // Get front end data
        $data = $request->request->get('category');
        //Note: you can not use $request->query->get('category') since your data
        // // is sent to this api by POST method not GET
        //$data = $request->query->get('category');
        
        if (isset($data['id'])) {
            // Find a category for edit
            $category = $em->getRepository('ProductBundle:Category')->find($data['id']);
        } else {
            // Create a new Category object for add
            $category = new Category();
        }

        $category->setName($data['name']);
        
        // Persist $category
        $em->persist($category);
        
        $em->flush();
        
        //You can expose whatever you want to your frontend here, such as categoryId in this case
        return array(
            'id' => $category->getId(),
            'name' => $category->getName()
            );
    }
}


