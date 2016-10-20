<?php

namespace LabelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Library\Base\BaseEntity;

/**
 * LabelRelation
 *
 * @ORM\Table(name="lola_label_relation")
 * @ORM\Entity(repositoryClass="LabelBundle\Repository\LabelRelationRepository")
 */
class LabelRelation extends BaseEntity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="entityId", type="integer")
     */
    private $entityId;

    /**
     * @var string
     *
     * @ORM\Column(name="entityName", type="string", length=255)
     */
    private $entityName;

    /**
     * @ORM\ManyToOne(targetEntity="Label", inversedBy="labelRelations")
     * @ORM\JoinColumn(name="label_id", referencedColumnName="id")
     */
    private $label;
    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set entityId
     *
     * @param integer $entityId
     *
     * @return LabelRelation
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;

        return $this;
    }

    /**
     * Get entityId
     *
     * @return int
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * Set entityName
     *
     * @param string $entityName
     *
     * @return LabelRelation
     */
    public function setEntityName($entityName)
    {
        $this->entityName = $entityName;

        return $this;
    }

    /**
     * Get entityName
     *
     * @return string
     */
    public function getEntityName()
    {
        return $this->entityName;
    }

    /**
     * Set label
     *
     * @param \LabelBundle\Entity\Label $label
     *
     * @return LabelRelation
     */
    public function setLabel(\LabelBundle\Entity\Label $label = null)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return \LabelBundle\Entity\Label
     */
    public function getLabel()
    {
        return $this->label;
    }
}
