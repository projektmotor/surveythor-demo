<?php
namespace PM\SurveythorBundle\Entity\ResultItems;

use PM\SurveythorBundle\Entity\Answer;
use PM\SurveythorBundle\Entity\ResultItem;
use PM\SurveythorBundle\Entity\SurveyItems\Question;

/**
 * TextAnswer
 */
class TextAnswer implements Answer
{
    /**
     * @var int $id
     */
    private $id;

    /**
     * @var string
     */
    private $value;

    /**
     * @var Question
     */
    private $question;

    /**
     * @var ResultItem
     */
    private $resultItem;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $value
     *
     * @return TextAnswer
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
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
}
