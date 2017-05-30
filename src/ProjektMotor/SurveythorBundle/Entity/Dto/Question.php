<?php
namespace PM\SurveythorBundle\Entity\Dto;

/**
 * Question
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class Question
{
    /**
     * @var string
     */
    private $text;

    /**
     * @var array
     */
    private $answers;

    /**
     * Get answers.
     *
     * @return answers.
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * Set answers.
     *
     * @param answers the value to set.
     */
    public function setAnswers($answers)
    {
        $this->answers = $answers;
    }

    /**
     * Get text.
     *
     * @return text.
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set text.
     *
     * @param text the value to set.
     */
    public function setText($text)
    {
        $this->text = $text;
    }
}
