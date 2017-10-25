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
     * @var SurveyItem[]|ArrayCollection
     */
    private $surveyItems;

    /**
     * @var ResultRange[]|ArrayCollection
     */
    private $resultRanges;

    /**
     * @var Result[]|ArrayCollection
     */
    private $results;

    public function __toString()
    {
        return get_class($this);
    }

    public function __construct()
    {
        $this->surveyItems = new ArrayCollection();
        $this->resultRanges = new ArrayCollection();
        $this->results = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
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
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
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
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
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
    //public function validate(ExecutionContextInterface $context, $payload)
    //{
    //    if ($this->getSurveyItems()->count() < 1) {
    //        $context->buildViolation('A Survey should have at least one SurveyItem')
    //            ->atPath('surveyItems')
    //            ->addViolation();
    //    } else {
    //        foreach ($this->getSurveyItems() as $surveyItem) {
    //            if (get_class($surveyItem) == Question::class) {
    //                if ($surveyItem->getText() == '') {
    //                    $context->buildViolation('A surveyItem should have a text.')
    //                        ->atPath('surveyItems')
    //                        ->addViolation();
    //                }
    //            }
    //        }
    //    }
    //}

    /**
     * @return int
     */
    public function getMaxPoints()
    {
        $points = 0;
        foreach ($this->surveyItems as $surveyItem) {
            $points = $points + $surveyItem->getMaxPoints();
        }

        return $points;
    }

    /**
     * @param SurveyItem $item
     *
     * @return SurveyItem|null
     */
    public function getNextItem(SurveyItem $item)
    {
        while ($current = $this->surveyItems->current()) {
            $next = $this->surveyItems->next();
            if ($current->getId() == $item->getId()) {
                // rewind pointer
                $this->surveyItems->first();
                return $next;
            }
        }
        $this->surveyItems->first();

        return null;
    }

    /**
     * @param SurveyItem $item
     *
     * @return SurveyItem|null
     */
    public function getPrevItem(SurveyItem $item)
    {
        while ($current = $this->surveyItems->current()) {
            $next = $this->surveyItems->next();

            if ($next && $next->getId() == $item->getId()) {
                // rewind pointer
                $this->surveyItems->first();
                return $current;
            }
        }
        $this->surveyItems->first();

        return null;
    }


    /**
     * @return Result[]|ArrayCollection
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @param Result $result
     *
     * @return Survey
     */
    public function addResult(Result $result)
    {
        if (!$this->results->contains($result)) {
            $this->results->add($result);
            $result->setSurvey($this);
        }

        return $this;
    }

    /**
     * @param Result $result
     */
    public function removeResult(Result $result)
    {
        $this->results->removeElement($result);
    }

    /**
     * @return SurveyItem[]|ArrayCollection
     */
    public function getSurveyItems()
    {
        return $this->surveyItems;
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
     * @return SurveyItem
     */
    public function getFirstSurveyItem()
    {
        return $this->getSurveyItems()->first();
    }
}
