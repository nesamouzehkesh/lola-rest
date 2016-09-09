<?php

namespace CustomerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\FOSRestController;
use CustomerBundle\Entity\Customer;

class CustomerController extends FOSRestController
{
    /**
     * @ApiDoc()
     * 
     * @Get("/customers", name="api_admin_get_customers", options={ "method_prefix" = false })
    */
    public function getCustomersAction(Request $request)
    {
        // Search criteria
        // Get all query parameters
        $criteria = $request->query->all();
        
        $customers = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('CustomerBundle:Customer')
            ->getCustomers($criteria);
        
        return $customers;
    }
    
    /**
     * @ApiDoc()
     * 
     * @Get("/customer/{id}", defaults={"id": null}, name="api_admin_get_customer", options={ "method_prefix" = false })
    */
    public function getCustomerAction($id)
    {
        $customer = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('CustomerBundle:Customer')
            ->getCustomer($id);
        
        return $customer;
    }

}
