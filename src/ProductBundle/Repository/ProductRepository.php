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
        
        // Search by name if searchText is provided
        if (null !== $criteria) {
            if (isset($criteria['searchText']) && $criteria['searchText'] !== '') {
                $qb->andWhere('p.name LIKE :searchText')
                    ->setParameter('searchText', '%'. $criteria['searchText'] . '%');
            }
            if (isset($criteria['category']) && intval($criteria['category']) !== 0) {
                $qb->join('p.productCategories', 'pc')
                    ->andWhere('pc.category = :categoryId AND pc.deleted = 0')
                    ->setParameter('categoryId', $criteria['category']);
            } 
        }     
        
        $products = $qb->getQuery()->getScalarResult();
        foreach ($products as $key => $product) {
            
            $qb = $this->getEntityManager()
                ->createQueryBuilder()
                ->from('ProductBundle:ProductCategory', 'pc')
                ->select(
                      'c.id, '
                    . 'c.name '
                    )
                ->join('pc.category', 'c')    
                ->where('c.deleted = false AND pc.product = :productId AND pc.deleted = false')
                ->setParameter('productId', $product['id']);
                
            $categories = $qb->getQuery()->getScalarResult();
            $products[$key]['categories'] = $categories;
        }

        
        return $products;
    }
    
    /**
     * Testing with a manual array for frontend product categories listing
     * 
     * @return type
     */
    public function getStaticProducts($criteria = null)
    {
       return array(
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
        
        $product = $qb->getQuery()->getSingleResult();
        
        $qb = $this->getEntityManager()
            ->createQueryBuilder()
            ->from('ProductBundle:ProductCategory', 'pc')
            ->select(
                  'c.id, '
                . 'c.name '
                )
            ->join('pc.category', 'c')    
            ->where('c.deleted = false AND pc.product = :productId AND pc.deleted = false')
            ->setParameter('productId', $product['id']);

        $categories = $qb->getQuery()->getScalarResult();
        $product['categories'] = $categories;
        
        return $product;
    }    
}