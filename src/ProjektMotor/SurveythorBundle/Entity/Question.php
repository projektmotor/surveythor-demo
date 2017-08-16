<?php

namespace PM\SurveythorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

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
     * @var Survey
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

    /**
     * @var integer
     */
    private $sortOrder;

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
            $this->setSortOrder($this->survey->getQuestions()->count());
            return $this;
        }

        if (null !== $this->parentChoice) {
            $this->setSortOrder($this->parentChoice->getChildQuestions()->count());
            return $this;
        }

        // dis is needed for fixture loading, should never happen
        if (null !== $this->sortOrder) {
            return $this;
        }

        throw new \Exception('a question has to have a survey or a parent choice');
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
