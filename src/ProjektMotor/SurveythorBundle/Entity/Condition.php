<?php

namespace PM\SurveythorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PM\SurveythorBundle\Entity\Question;
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
     *  @var Question
     */
    private $question;

    /**
     * @var Choice[]
     */
    private $choices;

    /**
     * @var SurveyItem $item
     */
    private $item;


    public function __construct()
    {
        $this->choices = new ArrayCollection();
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
     * @return Question
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param Question $question
     */
    public function setQuestion(Question $question)
    {
        $this->question = $question;
    }

    /**
     * @param Choice $choice
     *
     * @return Question
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
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param SurveyItem
     */
    public function setItem(SurveyItem $item)
    {
        $this->item = $item;
    }
}
