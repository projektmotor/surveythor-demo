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
     * @var \PM\SurveythorBundle\Entity\Answer
     */
    private $answers;


    public function __construct()
    {
        $this->answers = new ArrayCollection();
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

    public function removeAnswer(\PM\SurveythorBundle\Entity\Answer $answer)
    {
        $this->answers->removeElement($answer);
    }

    public function addAnswer(\PM\SurveythorBundle\Entity\Answer $answer)
    {
        if (!$this->answers->contains($answer)) {
            $this->answers->add($answer);
            $answer->setResult($this);
        }
    }

    /**
     * Get answers.
     *
     * @return answers.
     */
    public function getAnswers()
    {
        return $this->answers;
    }
}
