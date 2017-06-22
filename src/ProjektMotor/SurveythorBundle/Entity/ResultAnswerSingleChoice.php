<?php
namespace PM\SurveythorBundle\Entity;

use PM\SurveythorBundle\Entity\Answer;

/**
 * ResultAnswerSingleChoice
 */
class ResultAnswerSingleChoice extends ResultAnswer
{
    /**
     * answer
     *
     * @var Answer
     */
    private $answer;

    public function __construct()
    {
        parent::__construct();
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
     * @param answer the value to set.
     */
    public function setAnswer(Answer $answer)
    {
        $this->answer = $answer;
    }
}
