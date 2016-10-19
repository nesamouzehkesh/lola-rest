<?php

namespace CustomerBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use CustomerBundle\Entity\Address;

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
     * @param type $customer
     * @param type $data
     * @param type $type
     * @param type $isPrimary
     * @return Address
     */
    public function makeAddress($customer, $data, $type, $isPrimary) 
    {
        if ($isPrimary) { 
            // select the previous primary address of this $type for this $customer 
            // and set it to false
            $previousAddress = $this->em
                ->getRepository('CustomerBundle:Address')
                ->getPrimaryAddress($customer, $type);
            
            if ($previousAddress instanceof Address) {
                $previousAddress->setPrimary(false); 
            }
        }
        
        $address = new Address();
        $address->setCustomer($customer);
        $address->setPrimary($isPrimary);
        $address->setType($type);
        $address->setStreet($data['street']);
        $address->setCity($data['city']);
        $address->setZip($data['zip']);
        $address->setCountry($data['country']);
        $address->setState($data['state']);

        $this->em->persist($address);
        $this->em->flush();

        return $address; //this will be your either $billing or $shipping object
    }
    
    /**
     * 
     * @param type $customer
     * @param type $type
     * @return type
     */
    public function getAddress($customer, $type) 
    {
        $address = $this->em
            ->getRepository('CustomerBundle:Address')
            ->getAddress($customer, $type);
        
        return $address;
    }     
}