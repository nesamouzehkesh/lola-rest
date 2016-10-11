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
    
    /**
     * @ApiDoc()
     * 
     * @Post("/customer", name="api_admin_post_customer", options={ "method_prefix" = false })
     */ 
    public function postCustomerAction(Request $request)
    {
       
        $em = $this->getDoctrine()->getManager();
        // Get front end data
        $data = $request->request->get('customer');
       
        
        if (isset($data['id'])) {
            // Find a customer for edit
            $customer = $em->getRepository('CustomerBundle:Customer')->find($data['id']);
        } else {
            // Create a new Theme object for add
            $customer = new Customer();
        }
        
        $customer->setFirstName($data['firstName']);
        $customer->setLastName($data['lastName']);
        $customer->setEmail($data['email']);
        $customer->setPhoneNumber($data['phoneNumber']);

        
        // Persist $theme
        $em->persist($customer);
        
        $em->flush();
        
        //You can expose whatever you want to your frontend here, such as themeId in this case
        return array(
            'firstName' => $customer->getFirstName(),
            'lastName' => $customer->getLastName()
            );
    }
    
     /**
     * @ApiDoc()
     * 
     * @Get("/shipping", name="api_admin_get_shipping", options={ "method_prefix" = false })
    */
    public function getCustomerAddressAction()
    {
        $customer = $this->get('customer.service')->getCustomer();

        $shippingInfo = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('CustomerBundle:Customer')
            ->getCustomerAddress($customer);
        
        return $shippingInfo;
    }
}
