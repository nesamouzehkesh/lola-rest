<?php

namespace ProductBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends EntityRepository
{
    /**
     * 
     * @return type
     */
    public function getProducts($criteria = null, $order = 'p.id')
    {

        $qb = $this->createQueryBuilder('p')
            ->select(
                  'p.id, '
                . 'p.name, '
                . 'p.description,'
                . 'p.price'
                )
            ->where('p.deleted = false')
            ->orderBy($order);
        
        //testing with a manual array for frontend product categories listing
       
        
       $productsArray = array(
                            array(
                                    'id' => '1',
                                    'name' => 'A',
                                    'description' =>'a',
                                    'categories' => array(
                                                        array('id' => '23', 
                                                              'name' => 'Cat1'
                                                            ),
                                                        array('id' => '24', 
                                                              'name' => 'Cat2'
                                                            )
                                                    )
                                ),
                            array(
                                    'id' => '2',
                                    'name' => 'B',
                                    'description' => 'b',
                                    'categories' => array(
                                                        array('id' => '17',
                                                            'name' => 'Cat3'),
                                                        array('id' => '17',
                                                            'name' => 'Cat3')
                                                    )
                                )
                        ); 
        
        // Search by name if searchText is provided
        if (null !== $criteria) {
            if (isset($criteria['searchText']) && $criteria['searchText'] !== '') {
                $qb->andWhere('p.name LIKE :searchText')
                    ->setParameter('searchText', '%'. $criteria['searchText'] .'%');
            }
            /*
            if (isset($criteria['category']) && intval($criteria['category']) !== 0) {
                
                $qb->
                    ->andWhere('p.name :searchText')
                    ->setParameter('searchText', '%'. $criteria['searchText'] .'%');
            } 
             * 
             */               
        }
        
       // return $qb->getQuery()->getScalarResult();
        
        return $productsArray;
    }
    
    /**
     * 
     * @return type
     */
    public function getProduct($id)
    {
        $qb = $this->createQueryBuilder('p')
            ->select(
                  'p.id, '
                . 'p.name, '
                . 'p.description,'
                . 'p.price'
                )
            ->where('p.id = :id')
            ->setParameter('id', $id);
        
        return $qb->getQuery()->getSingleResult();
    }    
}