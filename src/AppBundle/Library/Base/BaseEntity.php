<?php

namespace AppBundle\Library\Base;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
class BaseEntity
{
    /**
     * @ORM\Column(name="is_deleted", type="boolean", nullable=true, options={"default"= 0})
     */
    protected $deleted;

    /**
     * @ORM\Column(name="deleted_time", type="bigint", nullable=true)
     */
    protected $deletedTime;

    /**
     * @ORM\Column(name="created_time", type="bigint", nullable=true)
     */
    protected $createdTime;
    
    /**
     * 
     */
    public function __construct()
    {
        $this->deleted = false;
        $this->createdTime = time();
    }

    /**
     * Set deleted
     *
     * @param boolean $deleted
     * @return Rating
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    
        return $this;
    }

    /**
     * Get deleted
     *
     * @return boolean 
     */
    public function isDeleted()
    {
    	return $this->deleted;
    }

    /**
     * Get deleted
     *
     * @return boolean 
     */
    public function getDeleted()
    {
        return $this->deleted;
    }
    
    /**
     * Set deleted time as current time
     * 
     * @return \Library\BaseEntity
     */
    public function setDeletedTime()
    {
        $this->deletedTime = time();
        
        return $this;
    }
    
    /**
     * Get deletedTime
     * @return integer
     */
    public function getDeletedTime()
    {
        return $this->deletedTime;
    }   
    
    /**
     * Get createdTime
     * @return integer
     */
    public function getCreatedTime()
    {
        return $this->createdTime;
    }       
}