<?php

namespace LabelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Library\Base\BaseEntity;

/**
 * Label
 *
 * @ORM\Table(name="lola_label")
 * @ORM\Entity(repositoryClass="LabelBundle\Repository\LabelRepository")
 */
class Label extends BaseEntity
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    /**
     * @var int
     *
     * @ORM\Column(name="visibility", type="integer")
     */
    private $visibility;
    
    
    /**
     * @ORM\OneToMany(targetEntity="LabelRelation", mappedBy="label")
     */
    private $labelRelations;


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
     * Set name
     *
     * @param string $name
     *
     * @return Label
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Label
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set type
     *
     * @param integer $type
     *
     * @return Label
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set visibility
     *
     * @param integer $visibility
     *
     * @return Label
     */
    public function setVisibility($visibility)
    {
        $this->visibility = $visibility;

        return $this;
    }

    /**
     * Get visibility
     *
     * @return int
     */
    public function getVisibility()
    {
        return $this->visibility;
    }

    /**
     * Add labelRelation
     *
     * @param \LabelBundle\Entity\LabelRelation $labelRelation
     *
     * @return Label
     */
    public function addLabelRelation(\LabelBundle\Entity\LabelRelation $labelRelation)
    {
        $this->labelRelations[] = $labelRelation;

        return $this;
    }

    /**
     * Remove labelRelation
     *
     * @param \LabelBundle\Entity\LabelRelation $labelRelation
     */
    public function removeLabelRelation(\LabelBundle\Entity\LabelRelation $labelRelation)
    {
        $this->labelRelations->removeElement($labelRelation);
    }

    /**
     * Get labelRelations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLabelRelations()
    {
        return $this->labelRelations;
    }
}
