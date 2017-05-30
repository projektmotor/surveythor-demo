<?php
namespace PM\SurveythorBundle\Entity\Dto;

use PM\SurveythorBundle\Entity\Question;

/**
 * Survey
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class Survey
{
    /**
     * @var string $title
     */
    private $title;

    /**
     * @var array $questions
     */
    private $questions;

    /**
     * Get questions.
     *
     * @return questions.
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Set questions.
     *
     * @param questions the value to set.
     */
    public function setQuestions($questions)
    {
        $this->questions = $questions;
    }

    /**
     * Get title.
     *
     * @return title.
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title.
     *
     * @param title the value to set.
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
}
