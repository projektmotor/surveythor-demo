<?php

namespace PM\SurveythorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use PM\SurveythorBundle\Entity\Question;

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
     * @var SurveyItem[]|ArrayCollection
     */
    private $surveyItems;

    /**
     * @var ResultRange[]|ArrayCollection
     */
    private $resultRanges;

    public function __construct()
    {
        $this->surveyItems = new ArrayCollection();
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
     * @param SurveyItem $surveyItem
     *
     * @return Survey
     */
    public function addSurveyItem(SurveyItem $surveyItem)
    {
        if (!$this->surveyItems->contains($surveyItem)) {
            $this->surveyItems->add($surveyItem);
            $surveyItem->setSurvey($this);
        }

        return $this;
    }

    /**
     * @param SurveyItem $surveyItem
     */
    public function removeSurveyItem(SurveyItem $surveyItem)
    {
        $this->surveyItems->removeElement($surveyItem);
    }

    /**
     * @return SurveyItem[]|ArrayCollection
     */
    public function getSurveyItems()
    {
        return $this->surveyItems;
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
        if ($this->getSurveyItems()->count() < 1) {
            $context->buildViolation('A Survey should have at least one SurveyItem')
                ->atPath('surveyItems')
                ->addViolation();
        } else {
            foreach ($this->getSurveyItems() as $surveyItem) {
                if ($surveyItem->getText() == '') {
                    $context->buildViolation('A surveyItem should have a text.')
                        ->atPath('surveyItems')
                        ->addViolation();
                }
            }
        }
    }

    public function getMaxPoints()
    {
        $points = 0;
        foreach ($this->surveyItems as $surveyItem) {
            $points = $points + $surveyItem->getMaxPoints();
        }

        return $points;
    }

    public function getQuestions()
    {
        $questions = new ArrayCollection();
        foreach ($this->surveyItems as $item) {
            if ($item instanceof Question) {
                $questions->add($item);
            }
        }
    }
}
