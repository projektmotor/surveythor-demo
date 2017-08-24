<?php
namespace PM\SurveythorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * SurveyItem
 */
abstract class SurveyItem
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var Survey
     */
    private $survey;

    /**
     * @var integer
     */
    private $sortOrder;

    /**
     * @var Condition[]|Arraycollection
     */
    private $conditions;

    public function __construct()
    {
        $this->conditions = new ArrayCollection();
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
     * Get survey.
     *
     * @return Survey
     */
    public function getSurvey()
    {
        return $this->survey;
    }

    /**
     * Set survey.
     *
     * @param Survey $survey
     *
     * @return Question
     */
    public function setSurvey($survey)
    {
        $this->survey = $survey;

        return $this;
    }

    /**
     * @return integer
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * @param integer $sortOrder
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;
    }

    public function setInitialSortOrder()
    {
        if (null !== $this->survey) {
            $this->setSortOrder($this->survey->getSurveyItems()->count());
            return $this;
        }

        #if (null !== $this->parentChoice) {
        #    $this->setSortOrder($this->parentChoice->getChildQuestions()->count());
        #    return $this;
        #}

        // dis is needed for fixture loading, should never happen
        if (null !== $this->sortOrder) {
            return $this;
        }

        throw new \Exception('a question has to have a survey or a parent choice');
    }

    /**
     * @param Condition $condition
     *
     * @return Question
     */
    public function addCondition(Condition $condition)
    {
        if (!$this->conditions->contains($condition)) {
            $this->conditions->add($condition);
            $condition->setQuestion($condition->getQuestion());
        }

        return $this;
    }

    /**
     * @param Condition $condition
     */
    public function removeCondition(Condition $condition)
    {
        $this->conditions->removeElement($condition);
    }

    /**
     * Get conditions.
     *
     * @return Condition[]|ArrayCollection
     */
    public function getConditions()
    {
        return $this->conditions;
    }
}
