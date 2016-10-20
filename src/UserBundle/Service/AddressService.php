<?php

namespace UserBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use UserBundle\Entity\Address;

class AddressService
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
     * @param type $user
     * @param type $data
     * @param type $type
     * @param type $isPrimary
     * @return Address
     */
    public function makeAddress($user, $data, $type, $isPrimary) 
    {
        if ($isPrimary) { 
            // select the previous primary address of this $type for this $user 
            // and set it to false
            $previousAddress = $this->em
                ->getRepository('UserBundle:Address')
                ->getPrimaryAddress($user, $type);
            
            if ($previousAddress instanceof Address) {
                $previousAddress->setPrimary(false); 
            }
        }
        
        $address = new Address();
        $address->setUser($user);
        $address->setPrimary($isPrimary);
        $address->setType($type);
        $address->setStreet($data['street']);
        $address->setCity($data['city']);
        $address->setZip($data['zip']);
        $address->setCountry($data['country']);
        $address->setState($data['state']);

        $this->em->persist($address);
        $this->em->flush();
        
        // this will be your either $billing or $shipping object
        return $address;
    }
    
    /**
     * 
     * @param type $user
     * @param type $type
     * @return type
     */
    public function getAddress($user, $type) 
    {
        $address = $this->em
            ->getRepository('UserBundle:Address')
            ->getAddress($user, $type);
        
        return $address;
    }     
}