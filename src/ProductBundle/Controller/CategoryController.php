<?php

namespace ProductBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;

class CategoryController extends FOSRestController
{
    /**
    * @ApiDoc()
    */
    public function getCategoriesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em
            ->getRepository('ProductBundle:ProductCategory')
            ->getCategories();
        
        return $categories;
    }
}
