<?php
namespace PM\SurveythorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PM\SurveythorBundle\Entity\ResultItems\MultipleChoiceAnswer;
use PM\SurveythorBundle\Entity\ResultItems\SingleChoiceAnswer;
use PM\SurveythorBundle\Entity\ResultItems\TextAnswer;
use PM\SurveythorBundle\Entity\ResultItems\TextItem;

/**
 * ResultItem
 */
class ResultItem
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var Result
     */
    protected $result;

    /**
     * @var int
     */
    protected $sortOrder;

    /**
     * @var SingleChoiceAnswer
     */
    protected $singleChoiceAnswer;

    /**
     * @var MultipleChoiceAnswer
     */
    protected $multipleChoiceAnswer;

    /**
     * @var TextAnswer
     */
    protected $textAnswer;

    /**
     * @var TextItem
     */
    protected $textItem;

    /**
     * @var SurveyItem
     */
    protected $surveyItem;

    /**
     * @var ResultItem[]|ArrayCollection
     */
    protected $childItems;

    /**
     * @var ResultItem
     */
    protected $parentItem;


    public function __construct()
    {
        $this->childItems = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Result
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param Result $result
     */
    public function setResult(Result $result)
    {
        $this->result = $result;
    }

    /**
     * @return int
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * @param integer $sortOrder
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;
    }
    
    /**
     * @return SingleChoiceAnswer
     */
    public function getSingleChoiceAnswer()
    {
        return $this->singleChoiceAnswer;
    }
    
    /**
     * @param SingleChoiceAnswer $singleChoiceAnswer
     */
    public function setSingleChoiceAnswer(SingleChoiceAnswer $singleChoiceAnswer)
    {
        $this->singleChoiceAnswer = $singleChoiceAnswer;
        $singleChoiceAnswer->setResultItem($this);
    }
    
    /**
     * @return MultipleChoiceAnswer
     */
    public function getMultipleChoiceAnswer()
    {
        return $this->multipleChoiceAnswer;
    }
    
    /**
     * @param MultipleChoiceAnswer $multipleChoiceAnswer
     */
    public function setMultipleChoiceAnswer(MultipleChoiceAnswer $multipleChoiceAnswer)
    {
        $this->multipleChoiceAnswer = $multipleChoiceAnswer;
        $multipleChoiceAnswer->setResultItem($this);
    }

    /**
     * @return TextAnswer
     */
    public function getTextAnswer()
    {
        return $this->textAnswer;
    }

    /**
     * @param TextAnswer $textAnswer
     */
    public function setTextAnswer(TextAnswer $textAnswer)
    {
        $this->textAnswer = $textAnswer;
        $textAnswer->setResultItem($this);
    }

    /**
     * @return TextItem
     */
    public function getTextItem()
    {
        return $this->textItem;
    }

    /**
     * @param TextItem $textItem
     */
    public function setTextItem(TextItem $textItem)
    {
        $this->textItem = $textItem;
        $textItem->setResultItem($this);
    }

    /**
     * @return ArrayCollection|ResultItem[]|MultipleChoiceAnswer|SingleChoiceAnswer|TextAnswer|TextItem
     */
    public function getContent()
    {
        if (!is_null($this->singleChoiceAnswer)) {
            return $this->getSingleChoiceAnswer();
        }
        if (!is_null($this->multipleChoiceAnswer)) {
            return $this->multipleChoiceAnswer;
        }
        if (!is_null($this->textAnswer)) {
            return $this->textAnswer;
        }
        if (!is_null($this->textItem)) {
            return $this->textItem;
        }
        if (!is_null($this->childItems)) {
            return $this->childItems;
        }
    }

    /**
     * @return ResultItem[]|ArrayCollection
     */
    public function getChildItems()
    {
        return $this->childItems;
    }

    /**
     * @param ResultItem $resultItem
     *
     * @return ResultItem
     */
    public function addChildItem(ResultItem $resultItem)
    {
        if (!$this->childItems->contains($resultItem)) {
            $this->childItems->add($resultItem);
            $resultItem->setParentItem($this);
        }

        return $this;
    }

    /**
     * @param ResultItem $resultItem
     */
    public function removeChildItem(ResultItem $resultItem)
    {
        $this->childItems->removeElement($resultItem);
    }

    /**
     * @return ResultItem
     */
    public function getParentItem()
    {
        return $this->parentItem;
    }

    /**
     * @param ResultItem $parentItem
     */
    public function setParentItem($parentItem)
    {
        $this->parentItem = $parentItem;
    }
    
    /**
     * @return SurveyItem
     */
    public function getSurveyItem()
    {
        return $this->surveyItem;
    }
    
    /**
     * @param SurveyItem $surveyItem
     */
    public function setSurveyItem(SurveyItem $surveyItem)
    {
        $this->surveyItem = $surveyItem;
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return $this->childItems->count() > 0;
    }
}
