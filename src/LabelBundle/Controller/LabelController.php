<?php

namespace LabelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\FOSRestController;
use LabelBundle\Entity\Label;
use LabelBundle\Entity\LabelRelation;

class LabelController extends FOSRestController
{
    /**
     * @ApiDoc()
     * 
     * @Get("/labels", name="api_admin_get_labels", options={ "method_prefix" = false })
    */
    public function getLabelsAction(Request $request)
    {
        // Search criteria
        // Get all query parameters
        $criteria = $request->query->all();
        
        $labels = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('LabelBundle:Label')
            ->getLabels($criteria);
        
        return $labels;
        
    }
    
     /**
     * @ApiDoc()
     * 
     * @Get("/label/{id}", defaults={"id": null}, name="api_admin_get_label", options={ "method_prefix" = false })
    */
    public function getLabelAction($id)
    {
        $label = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('LabelBundle:Label')
            ->getLabel($id);
        
        return $label;
    }

    /**
     * @ApiDoc()
     * 
     * @Post("/label", name="api_admin_post_label", options={ "method_prefix" = false })
     */ 
    public function postLabelAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        // Get front end data
        $data = $request->request->get('label');
        //Note: you can not use $request->query->get('theme') since your data
        // // is sent to this api by POST method not GET
        //$data = $request->query->get('theme');
        
        if (isset($data['id'])) {
            // Find a product for edit
            $label = $em->getRepository('LabelBundle:Label')->find($data['id']);
        } else {
            // Create a new Theme object for add
            $label = new Label();
        }
        
        $label->setName($data['name']);
        $label->setDescription($data['description']);
        
        // Persist $theme
        $em->persist($label);
        
        $em->flush();
        
        //You can expose whatever you want to your frontend here, such as themeId in this case
        return array(
            'name' => $label->getName(),
            'description' => $label->getDescription()
            );
    }
}

