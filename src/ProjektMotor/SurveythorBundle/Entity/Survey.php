<?php
namespace PM\SurveythorBundle\Entity;

use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $questions;

    /**
     * @var \Doctrine\Common\Collections\Collection
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
     * @param \PM\SurveythorBundle\Entity\Question $question
     * @return Survey
     */
    public function addQuestion(\PM\SurveythorBundle\Entity\Question $question)
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
            $question->setSurvey($this);
        }

        return $this;
    }

    /**
     * @param \PM\SurveythorBundle\Entity\Question $question
     */
    public function removeQuestion(\PM\SurveythorBundle\Entity\Question $question)
    {
        $this->questions->removeElement($question);
    }

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
     * Get resultRanges.
     *
     * @return resultRanges.
     */
    public function getResultRanges()
    {
        return $this->resultRanges;
    }

    /**
     * @param \PM\SurveythorBundle\Entity\ResultRange $range
     * @return Survey
     */
    public function addResultRange(\PM\SurveythorBundle\Entity\ResultRange $range)
    {
        if (!$this->resultRanges->contains($range)) {
            $this->resultRanges->add($range);
            $range->setSurvey($this);
        }

        return $this;
    }

    /**
     * @param \PM\SurveythorBundle\Entity\ResultRange $range
     */
    public function removeResultRange(\PM\SurveythorBundle\Entity\ResultRange $range)
    {
        $this->resultRanges->removeElement($range);
    }

    public function validate(ExecutionContextInterface $context, $payload)
    {
        if ($this->getQuestions()->count() < 1) {
            $context->buildViolation('A Survey should have at least one Question')
                ->atPath('questions')
                ->addViolation()
            ;
        } else {
            foreach ($this->getQuestions() as $question) {
                if ($question->getText() == '') {
                    $context->buildViolation('A question should have a text.')
                        ->atPath('questions')
                        ->addViolation()
                        ;
                }
            }
        }
    }
}
