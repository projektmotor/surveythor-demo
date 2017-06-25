<?php
namespace PM\SurveythorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PM\SurveythorBundle\Entity\Result;
use PM\SurveythorBundle\Entity\Qestion;

/**
 * Answer
 */
abstract class Answer
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var Result
     */
    private $result;

    /**
     * @var Question
     */
    private $question;

    /**
     * @var Answer
     */
    private $childAnswers;

    /**
     * @var Answer
     */
    private $parentAnswer;

    /**
     * @var integer
     */
    private $position;


    public function __construct()
    {
        $this->childAnswers = new ArrayCollection();
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
     * Get result.
     *
     * @return result.
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set result.
     *
     * @param result the value to set.
     */
    public function setResult(Result $result)
    {
        $this->result = $result;
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
    public function setQuestion(Question $question)
    {
        $this->question = $question;
    }

    /**
     * Get childAnswers.
     *
     * @return childAnswers.
     */
    public function getChildAnswers()
    {
        return $this->childAnswers;
    }

    public function addChildAnswer(Answer $childAnswer)
    {
        if (!$this->childAnswers->contains($childAnswer)) {
            $this->childAnswers->add($childAnswer);
            $childAnswer->setParentAnswer($this);
        }
    }

    public function removeChildAnswer(Answer $childAnswer)
    {
        $this->childAnswers->remove($childAnswer);
    }

    /**
     * Get parentAnswer.
     *
     * @return parentAnswer.
     */
    public function getParentAnswer()
    {
        return $this->parentAnswer;
    }

    /**
     * Set parentAnswer.
     *
     * @param parentAnswer the value to set.
     */
    public function setParentAnswer(Answer $parentAnswer)
    {
        $this->parentAnswer = $parentAnswer;
    }

    /**
     * Get position.
     *
     * @return position.
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set position.
     *
     * @param position the value to set.
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }
}
