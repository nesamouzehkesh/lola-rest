<?php

namespace PageBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\FOSRestController;
use PageBundle\Entity\Page;

class PageAdminController extends FOSRestController
{
    /**
     * @ApiDoc()
     * 
     * @Get("/pages", name="api_admin_page_get_pages", options={ "method_prefix" = false })
    */
    public function getPagesAction(Request $request)
    {
        // Search criteria
        // Get all query parameters
        $criteria = $request->query->all();
        
        $pages = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('PageBundle:Page')
            ->getPages($criteria);
        
        return $pages;
    }
    
    /**
     * @ApiDoc()
     * 
     * @Get("/pages/{id}", defaults={"id": null}, name="api_admin_page_get_page", options={ "method_prefix" = false })
    */
    public function getPageAction($id)
    {
        $page = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('PageBundle:Page')
            ->getPage($id);
        
        return $page;
    }
    
    /**
     * 
     * @ApiDoc()
     * 
     * @Delete("/pages/{id}", name="api_admin_page_delete_page", options={ "method_prefix" = false })
     */ 
    public function deletePageAction($id)
    {
        // Get a page from page service. 
        $page = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('PageBundle:Page')
            ->find($id);

        // Use deleteEntity function in app.service to delete this entity        
        $this->get('app.service')->deleteEntity($page);
        
        // There is a debate if this should be a 404 or a 204
        // see http://leedavis81.github.io/is-a-http-delete-requests-idempotent/
        return $this->routeRedirectView(
            'api_admin_get_page', 
            array(), 
            Response::HTTP_NO_CONTENT
            );        
    } 
    
    /**
     * @ApiDoc()
     * 
     * @Post("/pages", name="api_admin_page_post_page", options={ "method_prefix" = false })
     */ 
    public function postPageAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        // Get front end data
        $data = $request->request->get('page');
        //Note: you can not use $request->query->get('page') since your data
        // // is sent to this api by POST method not GET
        //$data = $request->query->get('page');
        
        if (isset($data['id'])) {
            // Find a product for edit
            $page = $em->getRepository('PageBundle:Page')->find($data['id']);
        } else {
            // Create a new Page object for add
            $page = new Page();
        }

        $page->setName($data['name']);
        $page->setDescription($data['description']);
        
        // Persist $page
        $em->persist($page);
        $em->flush();
        
        // You can expose whatever you want to your frontend here, such as 
        // pageId in this case
        return array(
            'id' => $page->getId(),
            'name' => $page->getName()
            );
    }
}
