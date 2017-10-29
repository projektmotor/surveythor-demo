<?php

namespace AppBundle\Entity;

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
     * @var ResultItem[]|ArrayCollection
     */
    private $resultItems;

    /**
     * @var Survey
     */
    private $survey;


    public function __construct()
    {
        $this->resultItems = new ArrayCollection();
    }

    /**
     * @param Survey $survey
     *
     * @return Result
     */
    public static function createBySurvey(Survey $survey)
    {
        $result = new self();
        $result->setSurvey($survey);

        $isFirst = true;
        foreach ($survey->getSurveyItems() as $surveyItem) {
            $resultItem = $surveyItem->createResultItem();
            if ($isFirst) {
                $resultItem->setIsCurrent();
                $isFirst = false;
            }
            $result->addResultItem($resultItem);
        }

        return $result;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \DateTime $created
     *
     * @return Result
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;

        return $this;
    }

    /**
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

    /**
     * @return Survey
     */
    public function getSurvey()
    {
        return $this->survey;
    }

    /**
     * @param Survey $survey
     */
    public function setSurvey(Survey $survey)
    {
        $this->survey = $survey;
    }

    /**
     * @return ResultItem
     */
    public function getCurrentResultItem()
    {
        foreach ($this->resultItems as $resultItem) {
            if ($resultItem->isCurrent()) {
                return $resultItem;
            }
        }

        // fallback use first item
        return $this->resultItems->first();
    }

    /**
     * @param ResultItem $newCurrentResultItem
     */
    public function setCurrentResultItem($newCurrentResultItem)
    {
        foreach ($this->resultItems as $resultItem) {
            if ($resultItem->isCurrent()) {
                $resultItem->setIsNotCurrent();
            }
            if ($resultItem->equals($newCurrentResultItem)) {
                $resultItem->setIsCurrent();
            }
        }
    }

    public function markNextResultItemAsCurrent()
    {
        $newCurrentResultItem = null;
        foreach ($this->resultItems as $key => $resultItem) {
            if ($resultItem->isCurrent()) {
                $resultItem->setIsNotCurrent();
                $newCurrentResultItem = $this->resultItems[$key + 1];
            }
        }
        $newCurrentResultItem->setIsCurrent();
    }

    public function markPreviousResultItemAsCurrent()
    {
        $newCurrentResultItem = null;
        foreach ($this->resultItems as $key => $resultItem) {
            if ($resultItem->isCurrent()) {
                $resultItem->setIsNotCurrent();
                $newCurrentResultItem = $this->resultItems[$key - 1];
            }
        }
        $newCurrentResultItem->setIsCurrent();
    }
}
