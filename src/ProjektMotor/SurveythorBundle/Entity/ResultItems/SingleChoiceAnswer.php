<?php
namespace PM\SurveythorBundle\Entity\ResultItems;

use PM\SurveythorBundle\Entity\SurveyItems\Question;
use PM\SurveythorBundle\Entity\Choice;
use PM\SurveythorBundle\Entity\ResultItem;

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

    private $question;

    private $resultItem;

    /**
     * Get id.
     *
     * @return id.
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
     * Get resultItem.
     *
     * @return resultItem.
     */
    public function getResultItem()
    {
        return $this->resultItem;
    }
    
    /**
     * Set resultItem.
     *
     * @param resultItem the value to set.
     */
    public function setResultItem($resultItem)
    {
        $this->resultItem = $resultItem;
    }
}
