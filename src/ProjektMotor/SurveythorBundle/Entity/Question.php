<?php

namespace PM\SurveythorBundle\Entity;

/**
 * Question
 */
class Question
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $text;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $answers;

    /**
     * @var \PM\SurveythorBundle\Entity\Survey
     */
    private $survey;

    public function __construct()
    {
        $this->answers = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set text
     *
     * @param string $text
     *
     * @return Question
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param \PM\SurveythorBundle\Entity\Answer $answer
     * @return Question
     */
    public function addAnswer(\PM\SurveythorBundle\Entity\Answer $answer)
    {
        if (!$this->answers->contains($answer)) {
            $this->answers->add($answer);
            $answer->setQuestion($this);
        }

        return $this;
    }

    /**
     * @param \PM\SurveythorBundle\Entity\Answer $answer
     */
    public function removeAnswer(\PM\SurveythorBundle\Entity\Answer $answer)
    {
        $this->answers->removeElement($answer);
    }

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
     * Get survey.
     *
     * @return survey.
     */
    public function getSurvey()
    {
        return $this->survey;
    }

    /**
     * Set survey.
     *
     * @param survey the value to set.
     */
    public function setSurvey($survey)
    {
        $this->survey = $survey;
    }
}
