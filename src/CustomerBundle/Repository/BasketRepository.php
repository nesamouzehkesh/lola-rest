<?php

namespace CustomerBundle\Repository;

/**
 * BasketRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BasketRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * 
     * @param type $customer
     * @return type
     */
    public function getBasketItem($customer, $product) //we need this function to 
    //check on the redundancy of an item in the basket when posting an item to the basket
    {
        $qb = $this->createQueryBuilder('basket')
            ->select(
                'basket.id,'
               .'basket.quantity,'
               .'p.name,'
               .'p.id,' 
               .'c.id' 
                )
            ->join('basket.product', 'p')
            ->join('basket.customer', 'c')
            ->where('basket.deleted = false AND basket.customer = :customer AND basket.product = :product')
            ->setParameter('customer', $customer)
            ->setParameter('product', $product);

        return $qb->getQuery()->getOneOrNullResult();
    }    
    
    /**
     * 
     * @param type $customer
     * @return type
     */
    public function getBasketItems($customer, $isJSON = true) //all the items for this customer 
    {
        $select = 'basket';
        if ($isJSON) {
           $select = 'basket.id,'
               .'basket.quantity,'
               .'p.name,'
               .'p.id as pid,' 
               .'c.id as cid';
        }

        $qb = $this->createQueryBuilder('basket')
            ->select($select)
            ->join('basket.product', 'p')
            ->join('basket.customer', 'c')
            ->where('basket.deleted = false AND basket.customer = :customer')
            ->setParameter('customer', $customer);
        
        if ($isJSON) {
            return $qb->getQuery()->getScalarResult();
        } else {
            return $qb->getQuery()->getResult();
        }
    } 
}
