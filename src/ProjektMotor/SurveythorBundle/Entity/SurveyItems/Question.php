<?php

namespace PM\SurveythorBundle\Entity\SurveyItems;

use Doctrine\Common\Collections\ArrayCollection;
use PM\SurveythorBundle\Entity\Choice;
use PM\SurveythorBundle\Entity\SurveyItem;

/**
 * Question
 */
class Question extends SurveyItem
{
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
    private $questionTemplate;

    /**
     * @var string
     */
    private $type;

    public function __construct()
    {
        $this->choices = new ArrayCollection();
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
     * Get questionTemplate.
     *
     * @return QuestionTemplate.
     */
    public function getQuestionTemplate()
    {
        return $this->questionTemplate;
    }

    /**
     * @param QuestionTemplate $questionTemplate
     */
    public function setQuestionTemplate($questionTemplate)
    {
        $this->questionTemplate = $questionTemplate;
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
}
