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

        $surveyItem = $survey->getFirstSurveyItem();
        $firstResultItem = $surveyItem->createResultItem();
        $result->addResultItem($firstResultItem);

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
        $resultItem = $this->resultItems->first();

        return $resultItem;
    }

//    /**
//     * @param ResultItem $resultItem
//     */
//    public function setCurrentResultItem($resultItem)
//    {
//        $this->resul
//
//        return $resultItem;
//    }
}
