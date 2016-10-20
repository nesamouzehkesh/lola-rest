<?php

namespace ThemeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\FOSRestController;
use ThemeBundle\Entity\Theme;

class ThemeAdminController extends FOSRestController
{
    /**
     * @ApiDoc()
     * 
     * @Get("/themes", name="api_admin_get_themes", options={ "method_prefix" = false })
     */
    public function getthemesAction(Request $request)
    {
        // Search criteria
        // Get all query parameters
        $criteria = $request->query->all();
        
        $themes = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('ThemeBundle:Theme')
            ->getThemes($criteria);
        
        return $themes;
        
    }
    
    /**
     * @ApiDoc()
     * 
     * @Get("/themes/{id}", defaults={"id": null}, name="api_admin_get_theme", options={ "method_prefix" = false })
     */
    public function getThemeAction($id)
    {
        $theme = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('ThemeBundle:Theme')
            ->getTheme($id);
        
        return $theme;
    }
    
     /**
     * 
     * @ApiDoc()
     * 
     * @Delete("/themes/{id}", name="api_admin_delete_theme", options={ "method_prefix" = false })
     */ 
    public function deleteThemeAction($id)
    {
        // Get a theme from theme service. 
        $theme = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('ThemeBundle:Theme')
            ->find($id);

        // Use deleteEntity function in app.service to delete this entity        
        $this->get('app.service')->deleteEntity($theme);
        
        return $this->routeRedirectView(
            'api_admin_get_themes', 
            array(), 
            Response::HTTP_NO_CONTENT
            );        
    } 
    
    /**
     * @ApiDoc()
     * 
     * @Post("/themes", name="api_admin_post_theme", options={ "method_prefix" = false })
     */ 
    public function postThemeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        // Get front end data
        $data = $request->request->get('theme');
        //Note: you can not use $request->query->get('theme') since your data
        // // is sent to this api by POST method not GET
        //$data = $request->query->get('theme');
        
        if (isset($data['id'])) {
            // Find a product for edit
            $theme = $em->getRepository('ThemeBundle:Theme')->find($data['id']);
        } else {
            // Create a new Theme object for add
            $theme = new Theme();
        }

        $theme->setName($data['name']);
        $theme->setContent($data['content']);
        
        // Persist $theme
        $em->persist($theme);
        $em->flush();
        
        return array(
            'id' => $theme->getId(),
            'name' => $theme->getName()
            );
    }
}



