<?php
namespace PM\SurveythorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PM\SurveythorBundle\Entity\Result;
use PM\SurveythorBundle\Entity\Answer;
use PM\SurveythorBundle\Entity\Qestion;

/**
 * ResultAnswer
 */
class ResultAnswer
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $value;

    /**
     * @var Result
     */
    private $result;

    /**
     * @var Answer
     */
    private $answer;

    /**
     * @var Question
     */
    private $question;

    /**
     * @var ResultAnswer
     */
    private $childAnswers;

    /**
     * @var ResultAnswer
     */
    private $parentAnswer;


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
     * Set value
     *
     * @param string $value
     *
     * @return ResultAnswer
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
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
     * Get answer.
     *
     * @return answer.
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set answer.
     *
     * @param Answer $answer.
     */
    public function setAnswer(Answer $answer)
    {
        $this->answer = $answer;
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

    public function addChildAnswer(ResultAnswer $childAnswer)
    {
        if (!$this->childAnswers->contains($childAnswer)) {
            $this->childAnswers->add($childAnswer);
            $this->setParentAnswer($this);
        }
    }

    public function removeChildAnswer(ResultAnswer $childAnswer)
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
    public function setParentAnswer(ResultAnswer $parentAnswer)
    {
        $this->parentAnswer = $parentAnswer;
    }
}
