<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use AppBundle\Library\Base\BaseEntity;

class AppService
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
     * Soft-delete an entity
     * 
     * @param type $entity
     */
    public function deleteEntity($entity)
    {
        if (!$entity instanceof BaseEntity) {
            return;
        }
        
        $entity->setDeleted(true);
        $entity->setDeletedTime();
        $this->em->flush();
    }
    
    /**
     * Persist an flush entity manager for this entity
     * 
     * @param type $entity
     * @param type $flush
     * @return \Library\Service\Helper
     */
    public function saveEntity($entity, $flush = true)
    {
        $this->em->persist($entity);
        if ($flush) {
            $this->em->flush();
        }
        
        return $this;
    }
}