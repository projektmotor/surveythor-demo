<?php

namespace AppBundle\Entity\ResultItems;

use AppBundle\Entity\Answer;
use AppBundle\Entity\Choice;
use AppBundle\Entity\ResultItem;
use AppBundle\Entity\SurveyItems\Question;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * MultipleChoiceAnswer
 */
class MultipleChoiceAnswer implements Answer
{
    /**
     * @var int $id
     */
    private $id;

    /**
     * @var Choice[]|ArrayCollection
     */
    private $choices;

    /**
     * @var Question
     */
    private $question;

    /**
     * @var ResultItem
     */
    private $resultItem;

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
     * @return Choice[]|ArrayCollection
     */
    public function getChoices()
    {
        return $this->choices;
    }

    /**
     * @param Choice $choice
     */
    public function addChoice(Choice $choice)
    {
        if (!$this->choices->contains($choice)) {
            $this->choices->add($choice);
        }
    }

    /**
     * @param Choice $choice
     */
    public function removeChoice(Choice $choice)
    {
        if ($this->choices->contains($choice)) {
            $this->choices->remove($choice);
        }
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
     * @return ResultItem
     */
    public function getResultItem()
    {
        return $this->resultItem;
    }

    /**
     * @param ResultItem $resultItem
     */
    public function setResultItem(ResultItem $resultItem)
    {
        $this->resultItem = $resultItem;
    }

    public function clearChoices()
    {
        $this->choices = new ArrayCollection();
    }
}
