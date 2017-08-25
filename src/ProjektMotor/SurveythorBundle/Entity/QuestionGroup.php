<?php
namespace PM\SurveythorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PM\SurveythorBundle\Entity\Question;

/**
 * QuestionGroup
 */
class QuestionGroup extends SurveyItem
{
    /**
     * @var string
     */
    private $header;

    /**
     * @var Question[]
     */
    private $questions;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
    }

    /**
     * Set header
     *
     * @param string $header
     *
     * @return QuestionGroup
     */
    public function setHeader($header)
    {
        $this->header = $header;

        return $this;
    }

    /**
     * Get header
     *
     * @return string
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param Question $question
     *
     * @return Question
     */
    public function addQuestion(Question $question)
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
        }

        return $this;
    }

    /**
     * @param Question $question
     */
    public function removeQuestion(Question $question)
    {
        $this->questions->removeElement($question);
    }

    /**
     * Get questions.
     *
     * @return Question[]|ArrayCollection
     */
    public function getQuestions()
    {
        return $this->questions;
    }
}
