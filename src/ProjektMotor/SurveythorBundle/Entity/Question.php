<?php

namespace PM\SurveythorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

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
     * @var Choice
     */
    private $parentChoice;

    /**
     * @var QuestionTemplate
     */
    private $template;

    /**
     * @var QuestionTemplate
     */
    private $childrenTemplate;


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
     * Get parentChoice.
     *
     * @return Choice parentChoice
     */
    public function getParentChoice()
    {
        return $this->parentChoice;
    }

    /**
     * Set parentChoice.
     *
     * @param Choice $parentChoice the value to set.
     */
    public function setParentChoice(Choice $parentChoice)
    {
        $this->parentChoice = $parentChoice;
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
     * Get childrenTemplate.
     *
     * @return childrenTemplate.
     */
    public function getChildrenTemplate()
    {
        return $this->childrenTemplate;
    }

    /**
     * @param QuestionTemplate $childrenTemplate
     */
    public function setChildrenTemplate($childrenTemplate)
    {
        $this->childrenTemplate = $childrenTemplate;
    }
}
