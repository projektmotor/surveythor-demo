<?php

namespace PM\SurveythorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PM\SurveythorBundle\Entity\Choice;
use PM\SurveythorBundle\Entity\SurveyItem;

/**
 * Condition
 */
class Condition
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var Choice[]
     */
    private $choices;

    /**
     * @var SurveyItem $item
     */
    private $surveyItem;

    /**
     * @var bool
     */
    private $isNegative;


    public function __construct()
    {
        $this->choices = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param Choice $choice
     *
     * @return Condition
     */
    public function addChoice(Choice $choice)
    {
        if (!$this->choices->contains($choice)) {
            $this->choices->add($choice);
        }

        return $this;
    }

    /**
     * @param Choice $choice
     */
    public function removeChoice(Choice $choice)
    {
        $this->choices->removeElement($choice);
    }

    /**
     * Get choices.
     *
     * @return Choice[]|ArrayCollection
     */
    public function getChoices()
    {
        return $this->choices;
    }

    /**
     * @return SurveyItem
     */
    public function getSurveyItem()
    {
        return $this->surveyItem;
    }

    /**
     * @param SurveyItem
     */
    public function setSurveyItem(SurveyItem $surveyItem)
    {
        $this->surveyItem = $surveyItem;
    }

    /**
     * @return bool
     */
    public function getIsNegative()
    {
        return $this->isNegative;
    }

    /**
     * @param bool $isNegative
     */
    public function setIsNegative($isNegative)
    {
        $this->isNegative = $isNegative;
    }
}
