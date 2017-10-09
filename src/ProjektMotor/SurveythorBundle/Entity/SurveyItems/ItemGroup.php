<?php
namespace PM\SurveythorBundle\Entity\SurveyItems;

use Doctrine\Common\Collections\ArrayCollection;
use PM\SurveythorBundle\Entity\SurveyItem;

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
}
