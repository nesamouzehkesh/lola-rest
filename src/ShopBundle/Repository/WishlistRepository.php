<?php

namespace ShopBundle\Repository;

/**
 * WishlistRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class WishlistRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * 
     * @param type $user
     * @return type
     */
    public function getWishlistItem($user, $product) 
    {
        $qb = $this->createQueryBuilder('wishlist')
            ->select('wishlist')
            ->where('wishlist.deleted = false AND wishlist.user = :user AND wishlist.product = :product')
            ->setParameter('user', $user)
            ->setParameter('product', $product);

        return $qb->getQuery()->getOneOrNullResult();
    }    
}