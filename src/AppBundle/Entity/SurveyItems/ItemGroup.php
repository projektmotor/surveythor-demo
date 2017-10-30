<?php

namespace AppBundle\Entity\SurveyItems;

use AppBundle\Entity\ResultItem;
use AppBundle\Entity\SurveyItem;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ItemGroup
 */
class ItemGroup extends SurveyItem
{
    /**
     * @var SurveyItem[]
     */
    private $surveyItems;

    public function __construct()
    {
        $this->surveyItems = new ArrayCollection();

        parent::__construct();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf(
            '%s (%s)',
            $this->getTitle(),
            $this->getSurvey()->getTitle()
        );
    }

    /**
     * @param SurveyItem $surveyItem
     *
     * @return ItemGroup
     */
    public function addSurveyItem(SurveyItem $surveyItem)
    {
        if (!$this->surveyItems->contains($surveyItem)) {
            $this->surveyItems->add($surveyItem);
            $surveyItem->setItemGroup($this);
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
     * @param int[]|null $ids
     *
     * @return int[]
     */
    public function getGroupIdsFromTop($ids = null)
    {
        $ids = $ids === null ? array() : $ids;

        array_push($ids, $this->id);
        if (!is_null($this->itemGroup)) {
            $ids = $this->itemGroup->getGroupIdsFromTop($ids);
        }

        return $ids;
    }

    /**
     * @return ResultItem
     */
    public function createResultItem()
    {
        $resultItem = new ResultItem();

        $childSurveyItems = $this->getSurveyItems();

        foreach ($childSurveyItems as $childSurveyItem) {
            $childResultItem = $childSurveyItem->createResultItem();

            $resultItem->addChildItem($childResultItem);
        }

        $resultItem->setSurveyItem($this);

        return $resultItem;
    }
}
