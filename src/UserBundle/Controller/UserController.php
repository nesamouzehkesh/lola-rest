<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\FOSRestController;

class UserController extends FOSRestController
{
    /**
     * @ApiDoc()
     * 
     * @Get("/users/{id}", defaults={"id": null}, name="api_shop_user_get_user", options={ "method_prefix" = false })
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
     * @Post("/users", name="api_shop_user_post_user", options={ "method_prefix" = false })
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
     * @ApiDoc()
     * 
     * @Get("/address", name="api_shop_user_get_address", options={ "method_prefix" = false })
     */
    public function getUserAddressAction()
    {
        $user = $this->get('user.service')->getUser();

        $addressInfo = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('UserBundle:User')
            ->getUserAddress($user);
        
        return $addressInfo;
    }
}
