<?php
namespace PM\SurveythorBundle\Entity\ResultItems;

use PM\SurveythorBundle\Entity\Choice;
use PM\SurveythorBundle\Entity\ResultItem;
use PM\SurveythorBundle\Entity\SurveyItems\Question;

/**
 * SingleChoiceAnswer
 */
class SingleChoiceAnswer
{
    /**
     * @var int $id
     */
    private $id;

    /**
     * @var Choice
     */
    private $choice;

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
     * @return Choice
     */
    public function getChoice()
    {
        return $this->choice;
    }

    /**
     * @param Choice $choice
     */
    public function setChoice(Choice $choice)
    {
        $this->choice = $choice;
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
     * @return ResultItem $resultItem
     */
    public function getResultItem()
    {
        return $this->resultItem;
    }
    
    /**
     * @param ResultItem $resultItem
     */
    public function setResultItem($resultItem)
    {
        $this->resultItem = $resultItem;
    }
}
