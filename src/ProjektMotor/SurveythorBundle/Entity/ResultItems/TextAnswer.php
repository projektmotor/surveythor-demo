<?php
namespace PM\SurveythorBundle\Entity\ResultItems;

use PM\SurveythorBundle\Entity\SurveyItems\Question;
use PM\SurveythorBundle\Entity\ResultItem;

/**
 * TextAnswer
 */
class TextAnswer
{
    /**
     * @var int $id
     */
    private $id;

    /**
     * @var string
     */
    private $value;

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
     * Set value
     *
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
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
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
