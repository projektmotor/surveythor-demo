<?php
namespace PM\SurveythorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PM\SurveythorBundle\Entity\Result;
use PM\SurveythorBundle\Entity\ResultItem;

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
     * @var integer
     */
    protected $sortOrder;

    protected $singleChoiceAnswer;
    protected $multipleChoiceAnswer;
    protected $textAnswer;
    protected $textItem;
    protected $surveyItem;

    /**
     * @var ResultItem[]
     */
    protected $childItems;

    /**
     * @var Resultitem
     */
    protected $parentItem;


    public function __construct()
    {
        $this->childItems = new ArrayCollection();
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
     * @return result.
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set result.
     *
     * @param result the value to set.
     */
    public function setResult(Result $result)
    {
        $this->result = $result;
    }

    /**
     * @return integer
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
     * Get singleChoiceAnswer.
     *
     * @return singleChoiceAnswer.
     */
    public function getSingleChoiceAnswer()
    {
        return $this->singleChoiceAnswer;
    }
    
    /**
     * Set singleChoiceAnswer.
     *
     * @param singleChoiceAnswer the value to set.
     */
    public function setSingleChoiceAnswer($singleChoiceAnswer)
    {
        $this->singleChoiceAnswer = $singleChoiceAnswer;
        $singleChoiceAnswer->setResultItem($this);
    }
    
    /**
     * Get multipleChoiceAnswer.
     *
     * @return multipleChoiceAnswer.
     */
    public function getMultipleChoiceAnswer()
    {
        return $this->multipleChoiceAnswer;
    }
    
    /**
     * Set multipleChoiceAnswer.
     *
     * @param multipleChoiceAnswer the value to set.
     */
    public function setMultipleChoiceAnswer($multipleChoiceAnswer)
    {
        $this->multipleChoiceAnswer = $multipleChoiceAnswer;
        $multipleChoiceAnswer->setResultItem($this);
    }

    /**
     * Get textAnswer.
     *
     * @return textAnswer.
     */
    public function getTextAnswer()
    {
        return $this->textAnswer;
    }

    /**
     * Set textAnswer.
     *
     * @param textAnswer the value to set.
     */
    public function setTextAnswer($textAnswer)
    {
        $this->textAnswer = $textAnswer;
        $textAnswer->setResultItem($this);
    }

    /**
     * Get textItem.
     *
     * @return textItem.
     */
    public function getTextItem()
    {
        return $this->textItem;
    }

    /**
     * Set textItem.
     *
     * @param textItem the value to set.
     */
    public function setTextItem($textItem)
    {
        $this->textItem = $textItem;
        $textItem->setResultItem($this);
    }

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
     * Get childItems.
     *
     * @return ResultItem[]|ArrayCollection
     */
    public function getChildItems()
    {
        return $this->childItems;
    }

    /**
     * @param ResultItem $resultItem
     *
     * @return Survey
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
     * Get parentItem.
     *
     * @return parentItem.
     */
    public function getParentItem()
    {
        return $this->parentItem;
    }

    /**
     * Set parentItem.
     *
     * @param parentItem the value to set.
     */
    public function setParentItem($parentItem)
    {
        $this->parentItem = $parentItem;
    }
    
    /**
     * Get surveyItem.
     *
     * @return surveyItem.
     */
    public function getSurveyItem()
    {
        return $this->surveyItem;
    }
    
    /**
     * Set surveyItem.
     *
     * @param surveyItem the value to set.
     */
    public function setSurveyItem($surveyItem)
    {
        $this->surveyItem = $surveyItem;
    }

    public function hasChildren()
    {
        return $this->childItems->count() > 0;
    }
}
