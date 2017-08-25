<?php
namespace PM\SurveythorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PM\SurveythorBundle\Entity\Answer;
use PM\SurveythorBundle\Entity\Result;

/**
 * AnswerGroup
 */
class AnswerGroup
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var Answer[]
     */
    private $answers;

    /**
     * @var Result
     */
    private $result;


    public function __construct()
    {
        $this->answers = new ArrayCollection();
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
     * Get answers.
     *
     * @return Answer[]|ArrayCollection answers.
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * @param Answer $answer
     */
    public function addAnswer(Answer $answer)
    {
        if (!$this->answers->contains($answer)) {
            $this->answers->add($answer);
        }
    }

    /**
     * @param Answer $answer
     */
    public function removeAnswer(Answer $answer)
    {
        $this->answers->remove($answer);
    }

    public function getResult()
    {
        return $this->result;
    }

    public function setResult($result)
    {
        $this->result = $result;
    }
}
