<?php

namespace PM\SurveythorBundle\Entity\SurveyItems;

use Doctrine\Common\Collections\ArrayCollection;
use PM\SurveythorBundle\Entity\SurveyItem;
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
     * @var Choice[]|ArrayCollection
     */
    private $choices;

    /**
     * @var QuestionTemplate
     */
    private $template;

    /**
     * @var string
     */
    private $type;

    /**
     * @var SurveyItem
     */
    private $surveyItem;


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
     * @param Choice $choice
     *
     * @return Question
     */
    public function addChoice(Choice $choice)
    {
        if (!$this->choices->contains($choice)) {
            $this->choices->add($choice);
            $choice->setQuestion($this);
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
     * Evaluates to true if question is multiple or single choice.
     *
     * @return bool
     */
    public function isChoiceQuestion()
    {
        return $this->getType() === 'mc' || $this->getType() === 'sc';
    }

    public function hasChoices()
    {
        return $this->choices->count() > 0;
    }

    public function getMaxPoints()
    {
        $points = 0;
        if ($this->hasChoices()) {
            foreach ($this->getChoices() as $choice) {
                $points = $points + $choice->getMaxPoints();
            }
        }

        return $points;
    }

    /**
     * Get template.
     *
     * @return template.
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param QuestionTemplate $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type.
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
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
}
