<?php

namespace PM\SurveythorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Result
 */
class Result
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \PM\SurveythorBundle\Entity\ResultAnswer
     */
    private $resultAnswers;


    public function __construct()
    {
        $this->resultAnswers = new ArrayCollection();
    }

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
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Result
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    public function setCreatedValue()
    {
        $this->setCreated(new \DateTime());
    }

    public function removeResultAnswer(\PM\SurveythorBundle\Entity\ResultAnswer $resultAnswer)
    {
        $this->resultAnswers->removeElement($resultAnswer);
    }

    public function addResultAnswer(\PM\SurveythorBundle\Entity\ResultAnswer $resultAnswer)
    {
        if (!$this->resultAnswers->contains($resultAnswer)) {
            $this->resultAnswers->add($resultAnswer);
            $resultAnswer->setResult($this);
        }
    }

    /**
     * Get resultAnswers.
     *
     * @return resultAnswers.
     */
    public function getResultAnswers()
    {
        return $this->resultAnswers;
    }
}
