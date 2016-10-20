<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\FOSRestController;

class UserAdminController extends FOSRestController
{
    /**
     * @ApiDoc()
     * 
     * @Get("/users", name="api_admin_user_get_users", options={ "method_prefix" = false })
    */
    public function getUsersAction(Request $request)
    {
        // Search criteria
        // Get all query parameters
        $criteria = $request->query->all();
        
        $users = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('UserBundle:User')
            ->getUsers($criteria);
        
        return $users;
    }
    
    /**
     * @ApiDoc()
     * 
     * @Get("/users/{id}", defaults={"id": null}, name="api_admin_user_get_user", options={ "method_prefix" = false })
    */
    public function getUserAction($id)
    {
        $user = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('UserBundle:User')
            ->getUser($id);
        
        return $user;
    }
    
    /**
     * @ApiDoc()
     * 
     * @Post("/users", name="api_admin_user_post_user", options={ "method_prefix" = false })
     */ 
    public function postUserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        // Get front end data
        $data = $request->request->get('user');
        
        // Find a user or create a new one
        if (isset($data['id'])) {
            // Find a user for edit
            $user = $this->get('user.service')->getUser($data['id']);
        } else {
            // Create a new Theme object for add
            $user = $this->get('user.service')->makeUser();
        }
        
        $user->setFirstName($data['firstName']);
        $user->setLastName($data['lastName']);
        $user->setEmail($data['email']);
        $user->setPhoneNumber($data['phoneNumber']);
        
        // Persist and save $user 
        $em->persist($user);
        $em->flush();
        
        // You can expose whatever you want to your frontend here, such as 
        // themeId in this case
        return array(
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName()
            );
    }
    
    /**
     * 
     * @ApiDoc()
     * 
     * @Delete("/users/{id}", name="api_admin_user_delete_user", options={ "method_prefix" = false })
     */ 
    public function deleteOrderAction($id)
    {
        // Get the order from order service. 
        $user = $this->get('user.service')->getUser($id);

        // Use deleteEntity function in app.service to delete this entity        
        $this->get('app.service')->deleteEntity($user);
        
        return $this->routeRedirectView(
            'api_admin_user_get_users', 
            array(), 
            Response::HTTP_NO_CONTENT
            );        
    }         
}
