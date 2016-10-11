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
    public function getProducts($criteria = null)
    {
        $qb = $this->createQueryBuilder('p') 
            ->select(
                  'p.id, '
                . 'p.name, '
                . 'p.description,'
                . 'p.price, '
                . 'b.name as brand'
                )
            ->join('p.brand', 'b')
            ->where('p.deleted = false');
        
        // Search by name if searchText is provided
        if (null !== $criteria) {
            if (isset($criteria['selectedSortCriteria'])) {
                //convert to string
                $sortCriteria = json_decode($criteria['selectedSortCriteria'], true);
                //double checking the parameters passed
                if (isset($sortCriteria['sort']) && isset($sortCriteria['order'])) {
                    $qb->orderBy('p.' . $sortCriteria['sort'], $sortCriteria['order']);
                }
            }     

            if (isset($criteria['brand']) && intval($criteria['brand']) !== 0) {
                $qb->andWhere('b.id = :brandId')
                    ->setParameter('brandId', $criteria['brand']);
            }
            
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

            
            $qb = $this->getEntityManager()
                ->createQueryBuilder()
                ->from('LabelBundle:LabelRelation', 'lr')
                ->select(
                      'l.id, '
                    . 'l.name '
                    )
                ->join('lr.label', 'l')    
                ->where('lr.deleted = false AND lr.entityId = :entityId AND lr.entityName = :entityName')
                ->setParameter('entityId', $product['id'])
                ->setParameter('entityName', 'product');

            $products[$key]['labels'] = $qb->getQuery()->getScalarResult();            
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
                . 'p.price, '
                . 'b.name as brand'
                )
            ->join('p.brand', 'b')
            ->where('p.id = :id')
            ->setParameter('id', $id);
        
        $product = $qb->getQuery()->getSingleResult();
        
        // generate category array for the product object
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
        
        //generate lable array for the product object
        $qb = $this->getEntityManager()
            ->createQueryBuilder()
            ->from('LabelBundle:LabelRelation', 'lr')
            ->select(
                  'l.id, '
                . 'l.name '
                )
            ->join('lr.label', 'l')    
            ->where('lr.deleted = false AND lr.entityId = :entityId AND lr.entityName = :entityName')
            ->setParameter('entityId', $product['id'])
            ->setParameter('entityName', 'product');

        $product['labels'] = $qb->getQuery()->getScalarResult();
        
        
        return $product;
    }    
}