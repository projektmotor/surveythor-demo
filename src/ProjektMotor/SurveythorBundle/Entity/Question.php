<?php

namespace PM\SurveythorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PM\SurveythorBundle\Entity\Choice;

/**
 * Question
 */
class Question
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $text;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $choices;

    /**
     * @var \PM\SurveythorBundle\Entity\Survey
     */
    private $survey;

    /**
     * @var Choice
     */
    private $parentChoice;

    /**
     * @var string
     */
    private $type;


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
     * Set text
     *
     * @param string $text
     *
     * @return Question
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param \PM\SurveythorBundle\Entity\Choice $choice
     * @return Question
     */
    public function addChoice(\PM\SurveythorBundle\Entity\Choice $choice)
    {
        if (!$this->choices->contains($choice)) {
            $this->choices->add($choice);
            $choice->setQuestion($this);
        }

        return $this;
    }

    /**
     * @param \PM\SurveythorBundle\Entity\Choice $choice
     */
    public function removeChoice(\PM\SurveythorBundle\Entity\Choice $choice)
    {
        $this->choices->removeElement($choice);
    }

    /**
     * Get choices.
     *
     * @return choices.
     */
    public function getChoices()
    {
        return $this->choices;
    }

    /**
     * Get survey.
     *
     * @return survey.
     */
    public function getSurvey()
    {
        return $this->survey;
    }

    /**
     * Set survey.
     *
     * @param survey the value to set.
     */
    public function setSurvey($survey)
    {
        $this->survey = $survey;

        return $this;
    }

    /**
     * Get parentChoice.
     *
     * @return parentChoice.
     */
    public function getParentChoice()
    {
        return $this->parentChoice;
    }

    /**
     * Set parentChoice.
     *
     * @param parentChoice the value to set.
     */
    public function setParentChoice($parentChoice)
    {
        $this->parentChoice = $parentChoice;
    }

    /**
     * Get type.
     *
     * @return type.
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type.
     *
     * @param type the value to set.
     */
    public function setType($type)
    {
        $this->type = $type;
    }
}
