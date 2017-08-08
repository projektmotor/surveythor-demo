<?php

namespace PM\SurveythorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Survey
 */
class Survey
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * @var Question[]|ArrayCollection
     */
    private $questions;

    /**
     * @var ResultRange[]|ArrayCollection
     */
    private $resultRanges;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->resultRanges = new ArrayCollection();
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
     * Set title
     *
     * @param string $title
     *
     * @return Survey
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Survey
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param Question $question
     *
     * @return Survey
     */
    public function addQuestion(Question $question)
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
            $question->setSurvey($this);
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
     * @return Question[]|ArrayCollection
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Get resultRanges.
     *
     * @return ResultRange[]|ArrayCollection
     */
    public function getResultRanges()
    {
        return $this->resultRanges;
    }

    /**
     * @param ResultRange $range
     *
     * @return Survey
     */
    public function addResultRange(ResultRange $range)
    {
        if (!$this->resultRanges->contains($range)) {
            $this->resultRanges->add($range);
            $range->setSurvey($this);
        }

        return $this;
    }

    /**
     * @param ResultRange $range
     */
    public function removeResultRange(ResultRange $range)
    {
        $this->resultRanges->removeElement($range);
    }

    /**
     * @param ExecutionContextInterface $context
     * @param $payload
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if ($this->getQuestions()->count() < 1) {
            $context->buildViolation('A Survey should have at least one Question')
                ->atPath('questions')
                ->addViolation();
        } else {
            foreach ($this->getQuestions() as $question) {
                if ($question->getText() == '') {
                    $context->buildViolation('A question should have a text.')
                        ->atPath('questions')
                        ->addViolation();
                }
            }
        }
    }

    public function getMaxPoints()
    {
        $points = 0;
        foreach ($this->questions as $question) {
            if ($question->hasChoices()) {
                $maxValue = 0;
                foreach ($question->getChoices() as $choice) {
                    $maxValue = $maxValue < $choice->getValue() ? $choice->getValue() : $maxValue;
                }
                $points = $points + $maxValue;
            }
        }

        return $points;
    }
}
