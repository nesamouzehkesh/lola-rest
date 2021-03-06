<?php

namespace CustomerBundle\Repository;

use \Doctrine\ORM\EntityRepository;

/**
 * AddressRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AddressRepository extends EntityRepository
{
    /**
     * 
     * @param type $customerId
     * @param type $addressType
     * 
     * @return Address|null
     */
    public function getAddress($customerId, $addressType)
    {
        $qb = $this->createQueryBuilder('ad')
            ->select('ad')
            ->join('ad.customer', 'cus')
            ->where('cus.id = :id AND ad.type = :type')
            ->setParameter('id', $customerId)
            ->setParameter('type', $addressType);
        
        return $qb->getQuery()->getOneOrNullResult();
    }
    
    /**
     * 
     * @param type $customer
     * @param type $addressType
     * @return type
     */
    public function getPrimaryAddress($customer, $addressType)
    {
        $qb = $this->createQueryBuilder('ad')
            ->select('ad')
            ->where('ad.customer = :customer AND ad.type = :type AND ad.primary = true')
            ->setParameter('customer', $customer)
            ->setParameter('type', $addressType);
        
        return $qb->getQuery()->getOneOrNullResult();
    }
} 
