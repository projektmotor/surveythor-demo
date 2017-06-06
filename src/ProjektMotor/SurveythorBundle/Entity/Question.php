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

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $parentAnswers;

    /**
     * @var string
     */
    private $type;


    public function __construct()
    {
        $this->answers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->parentAnswers = new \Doctrine\Common\Collections\ArrayCollection();
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

    /**
     * Get parentAnswers.
     *
     * @return parentAnswers.
     */
    public function getParentAnswers()
    {
        return $this->parentAnswers;
    }

    /**
     * Set parentAnswers.
     *
     * @param parentAnswers the value to set.
     */
    public function setParentAnswers($parentAnswers)
    {
        $this->parentAnswers = $parentAnswers;
    }

    /**
     * @param \PM\SurveythorBundle\Entity\Answer $answer
     * @return Question
     */
    public function addParentAnswer(\PM\SurveythorBundle\Entity\Answer $answer)
    {
        if (!$this->parentAnswers->contains($answer)) {
            $this->parentAnswers->add($answer);
            $answer->addChildQuestion($this);
        }

        return $this;
    }

    /**
     * @param \PM\SurveythorBundle\Entity\Answer $answer
     */
    public function removeParentAnswer(\PM\SurveythorBundle\Entity\Answer $answer)
    {
        $this->parentAnswers->removeElement($answer);
    }

    /**
     * Get type.
     *
     * @return type.
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type.
     *
     * @param type the value to set.
     */
    public function setType($type)
    {
        $this->type = $type;
    }
}
