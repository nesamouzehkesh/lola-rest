<?php

namespace LabelBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * LabelRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LabelRepository extends EntityRepository
{
    /**
    *
    * @param type $criteria
    * @return type
    */
    public function getLabels($criteria = null) 
    {
        $qb = $this->createQueryBuilder('label')
            ->select(
                'label.id, '
               .'label.name, '
               .'label.description ' 
            )
            ->where('label.deleted = false');

        $labels = $qb->getQuery()->getScalarResult();

        return $labels;
    }
    
     /**
     * 
     * @return type
     */
    public function getLabel($id)
    {
        $qb = $this->createQueryBuilder('l')
            ->select(
                  'l.id, '
                . 'l.name, '
                . 'l.description'
                )
            ->where('l.id = :id')
            ->setParameter('id', $id);
        
        $label = $qb->getQuery()->getSingleResult();
        
        return $label;
    }    
}
