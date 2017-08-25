<?php

namespace PM\SurveythorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PM\SurveythorBundle\Entity\Answer;
use PM\SurveythorBundle\Entity\AnswerGroup;

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
     * @var Answer[]|ArrayCollection
     */
    private $answers;

    /**
     * @var AnswerGroup[]|ArrayCollection
     */
    private $answerGroups;


    public function __construct()
    {
        $this->answers = new ArrayCollection();
        $this->answerGroups = new ArrayCollection();
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

    /**
     * @param Answer $answer
     */
    public function removeAnswer(Answer $answer)
    {
        $this->answers->removeElement($answer);
    }

    /**
     * @param Answer $answer
     */
    public function addAnswer(Answer $answer)
    {
        if (!$this->answers->contains($answer)) {
            $this->answers->add($answer);
            $answer->setResult($this);
            $answer->setPosition($this->getAnswers()->count());
        }
    }

    /**
     * @return Answer[]|ArrayCollection
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * @param AnswerGroup $answerGroup
     */
    public function removeAnswerGroup(AnswerGroup $answerGroup)
    {
        $this->answerGroups->removeElement($answerGroup);
    }

    /**
     * @param AnswerGroup $answerGroup
     */
    public function addAnswerGroup(AnswerGroup $answerGroup)
    {
        if (!$this->answerGroups->contains($answerGroup)) {
            $this->answerGroups->add($answerGroup);
            $answerGroup->setResult($this);
        }
    }

    /**
     * @return AnswerGroup[]|ArrayCollection
     */
    public function getAnswerGroups()
    {
        return $this->answerGroups;
    }
}
