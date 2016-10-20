<?php

namespace UserBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use UserBundle\Entity\User;

class UserService
{
    /**
     * Translator services
     * 
     * @var Translator $translator
     */
    protected $translator;  
    
    /**
     *
     * @var EntityManager $em
     */
    protected $em;

    /**
     * 
     * @param Translator $translator
     * @param EntityManager $em
     */
    public function __construct(
        Translator $translator, 
        EntityManager $em
        ) 
    {
        $this->translator = $translator;
        $this->em = $em;
    }   
    
    /**
     * 
     * @param int|null $id
     * @return type
     */
    public function getUser($id = null)
    {
        if (null === $id) {
            // get ID from request token
            //
            return $this->em
                ->getRepository('UserBundle:User')
                ->find(1);
        } else {
            return $this->em
                ->getRepository('UserBundle:User')
                ->find($id);
        }
    }
    
    /**
     * 
     * @param type $id
     * @return type
     */
    public function makeUser()
    {
        $user = new User();
        
        return $user;
    }    
}