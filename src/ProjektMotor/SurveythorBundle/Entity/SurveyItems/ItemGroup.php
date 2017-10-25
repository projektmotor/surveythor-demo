<?php

namespace PM\SurveythorBundle\Entity\SurveyItems;

use Doctrine\Common\Collections\ArrayCollection;
use PM\SurveythorBundle\Entity\ResultItem;
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

//        $resultTextItem = new ResultItem();
//        $resultTextItem->setText($this->getText());
//        $resultItem->setResultTextItem($resultTextItem);
//        $resultItem->setSurveyItem($this);

        return $resultItem;
    }

    /**
     * @return ResultItem
     */
    public function createResultItemT()
    {
        $resultItem = new ResultItem();

        $resultTextItem = new ResultTextItem();
        $resultTextItem->setText($this->getText());
        $resultItem->setResultTextItem($resultTextItem);
        $resultItem->setSurveyItem($this);

        return $resultItem;
    }

    /**
     * @return ResultItem
     * @throws \Exception
     */
    public function createResultItemC()
    {
        $resultItem = new ResultItem();

        switch ($this->getType()) {
            case 'mc':
                $answer = new MultipleChoiceAnswer();
                break;
            case 'sc':
                $answer = new SingleChoiceAnswer();
                break;
            case 'text':
                $answer = new TextAnswer();
                break;
            default:
                throw new \Exception('a question has to have a type');
                break;
        }

        $answer->setQuestion($this);

        $resultItem->setAnswer($answer);
        $resultItem->setSurveyItem($this);

        return $resultItem;
    }
}
