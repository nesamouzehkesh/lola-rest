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

    
}

