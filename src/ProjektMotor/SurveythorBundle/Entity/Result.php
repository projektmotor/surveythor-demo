<?php

namespace PM\SurveythorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PM\SurveythorBundle\Entity\ResultItem;
use PM\SurveythorBundle\Entity\Answer;
use PM\SurveythorBundle\Entity\Survey;

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
     * @var ResultItem[]
     */
    private $resultItems;

    /**
     * ævar Survey
     */
    private $survey;


    public function __construct()
    {
        $this->resultItems = new ArrayCollection();
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
     * @param ResultItem $resultItem
     */
    public function removeResultItem(ResultItem $resultItem)
    {
        $this->resultItems->removeElement($resultItem);
    }

    /**
     * @param ResultItem $resultItem
     */
    public function addResultItem(ResultItem $resultItem)
    {
        if (!$this->resultItems->contains($resultItem)) {
            $this->resultItems->add($resultItem);
            $resultItem->setSortOrder($this->resultItems->count());
            $resultItem->setResult($this);
        }
    }

    /**
     * @return ResultItem[]|ArrayCollection
     */
    public function getResultItems()
    {
        return $this->resultItems;
    }

    public function getAnswers()
    {
        $answers = new ArrayCollection();
        foreach ($this->resultItems as $resultItem) {
            if ($resultItem instanceof Answer) {
                $answers->add($resultItem);
            }
            if ($resultItem instanceof AnswerGroup) {
                foreach ($resultItem->getAnswers() as $answer) {
                    $answers->add($answer);
                }
                $answers->add($resultItem);
            }
        }

        return $answers;
    }

    public function getSurvey()
    {
        return $this->survey;
    }

    public function setSurvey(Survey $survey)
    {
        $this->survey = $survey;
    }
}
