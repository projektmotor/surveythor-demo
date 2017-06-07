<?php
namespace PM\SurveythorBundle\Entity;

use PM\SurveythorBundle\Entity\Result;
use PM\SurveythorBundle\Entity\Answer;

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
}
