<?php

namespace CftfBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * LsDefAssociationGrouping
 *
 * @ORM\Table(name="ls_def_association_grouping")
 * @ORM\Entity(repositoryClass="CftfBundle\Repository\LsDefAssociationGroupingRepository")
 *
 * @Serializer\ExclusionPolicy("all")
 */
class LsDefAssociationGrouping extends AbstractLsDefinition implements CaseApiInterface
{
    /**
     * @var LsDoc
     *
     * @ORM\ManyToOne(targetEntity="CftfBundle\Entity\LsDoc", inversedBy="associationGroupings")
     *
     * @Assert\NotNull()
     */
    private $lsDoc;

    /**
     * @return LsDoc
     */
    public function getLsDoc(): ?LsDoc
    {
        return $this->lsDoc;
    }

    /**
     * @param LsDoc $lsDoc
     *
     * @return LsDefAssociationGrouping
     */
    public function setLsDoc(?LsDoc $lsDoc)
    {
        $this->lsDoc = $lsDoc;

        return $this;
    }

    /**
     * Create a duplicate of the lsDefAssociationGrouping into a new document
     *
     * @param LsDoc $newLsDoc
     *
     * @return LsDefAssociationGrouping
     */
    public function duplicateToLsDoc(LsDoc $newLsDoc): LsDefAssociationGrouping
    {
        $newAssociationGrouping = clone $this;
        $newAssociationGrouping->setLsDoc($newLsDoc);
        return $newAssociationGrouping;
    }
}
