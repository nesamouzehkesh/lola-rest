<?php

namespace CustomerBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;

class CustomerService
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
     * @param type $id
     * @return type
     */
    public function getCustomer($id = null)
    {
        return $this->em
            ->getRepository('CustomerBundle:Customer')
            ->find(1);
    }
}