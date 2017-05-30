<?php

namespace PM\SurveythorBundle\Entity;

/**
 * Answer
 */
class Answer
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
    private $points;

    /**
     * @var string
     */
    private $event;

    /**
     * @var \PM\SurveythorBundle\Entity\Question
     */
    private $question;

    /**
     * @var \PM\SurveythorBundle\Entity\Question
     */
    private $childQuestion;


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
     * @return Answer
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
     * Set points
     *
     * @param integer $points
     *
     * @return Answer
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return int
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set event
     *
     * @param string $event
     *
     * @return Answer
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
     * @return question.
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set question.
     *
     * @param question the value to set.
     */
    public function setQuestion($question)
    {
        $this->question = $question;
    }

    /**
     * Get childQuestion.
     *
     * @return childQuestion.
     */
    public function getChildQuestion()
    {
        return $this->childQuestion;
    }

    /**
     * Set childQuestion.
     *
     * @param childQuestion the value to set.
     */
    public function setChildQuestion($childQuestion)
    {
        $this->childQuestion = $childQuestion;
    }
}
