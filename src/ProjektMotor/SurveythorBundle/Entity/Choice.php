<?php

namespace PM\SurveythorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Choice
 */
class Choice
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
     * @var int
     */
    private $value;

    /**
     * @var string
     */
    private $event;

    /**
     * @var Question
     */
    private $question;

    /**
     * @var Question[]|ArrayCollection
     */
    private $childQuestions;

    public function __construct()
    {
        $this->childQuestions = new ArrayCollection();
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
     * @return Choice
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
     * Set value
     *
     * @param int $value
     *
     * @return Choice
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return integer
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set event
     *
     * @param string $event
     *
     * @return Choice
     */
    public function setEvent($event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return string
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Get question.
     *
     * @return Question
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set question.
     *
     * @param Question $question the value to set.
     */
    public function setQuestion(Question $question)
    {
        $this->question = $question;
    }

    /**
     * Get childQuestions.
     *
     * @return Question[]|ArrayCollection childQuestions.
     */
    public function getChildQuestions()
    {
        return $this->childQuestions;
    }

    /**
     * Set childQuestions.
     *
     * @param Question[]|ArrayCollection $childQuestions
     */
    public function setChildQuestions($childQuestions)
    {
        $this->childQuestions = $childQuestions;
    }

    /**
     * @param Question $question
     *
     * @return Choice
     */
    public function addChildQuestion(Question $question)
    {
        if (!$this->childQuestions->contains($question)) {
            $this->childQuestions->add($question);
            $question->setParentChoice($this);
        }

        return $this;
    }

    /**
     * @param Question $question
     */
    public function removeChildQuestion(Question $question)
    {
        $this->childQuestions->removeElement($question);
    }

    /**
     * @return bool
     */
    public function hasChildQuestions()
    {
        return (! $this->getChildQuestions()->isEmpty());
    }
}
