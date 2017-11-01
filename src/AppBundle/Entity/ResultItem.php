<?php

namespace AppBundle\Entity;

use AppBundle\Entity\ResultItems\MultipleChoiceAnswer;
use AppBundle\Entity\ResultItems\ResultTextItem;
use AppBundle\Entity\ResultItems\SingleChoiceAnswer;
use AppBundle\Entity\ResultItems\TextAnswer;
use AppBundle\Entity\SurveyItems\SurveyTextItem;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @var bool
     */
    protected $isCurrent = false;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var bool
     */
    private $displayTitle = false;

    /**
     * @var string
     */
    protected $description;

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
     * @var ResultTextItem
     */
    protected $resultTextItem;

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
     * @param SurveyTextItem $surveyTextItem
     *
     * @return ResultItem
     */
    public function createBySurveyTextItem(SurveyTextItem $surveyTextItem)
    {
        $resultItem = new self();

        $resultTextItem = ResultTextItem::createBySurveyTextItem($surveyTextItem);
        $resultItem->setResultTextItem($resultTextItem);
        $resultItem->setSurveyItem($surveyTextItem);

        return $resultItem;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isCurrent()
    {
        return $this->isCurrent;
    }

    public function setIsCurrent()
    {
        $this->isCurrent = true;
    }

    public function setIsNotCurrent()
    {
        $this->isCurrent = false;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDisplayTitle()
    {
        return $this->displayTitle;
    }

    /**
     * @param string $displayTitle
     */
    public function setDisplayTitle($displayTitle)
    {
        $this->displayTitle = $displayTitle;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
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
     * @return ResultTextItem
     */
    public function getResultTextItem()
    {
        return $this->resultTextItem;
    }

    /**
     * @param ResultTextItem $resultTextItem
     */
    public function setResultTextItem(ResultTextItem $resultTextItem)
    {
        $this->resultTextItem = $resultTextItem;
        $resultTextItem->setResultItem($this);
    }

    /**
     * @return ArrayCollection|ResultItem[]|MultipleChoiceAnswer|SingleChoiceAnswer|TextAnswer|ResultTextItem
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
        if (!is_null($this->resultTextItem)) {
            return $this->resultTextItem;
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

    /**
     * @param Answer|MultipleChoiceAnswer|SingleChoiceAnswer|TextAnswer $answer
     */
    public function setAnswer(Answer $answer)
    {
        if ($answer instanceof MultipleChoiceAnswer) {
            $this->setMultipleChoiceAnswer($answer);
        }
        if ($answer instanceof SingleChoiceAnswer) {
            $this->setSingleChoiceAnswer($answer);
        }
        if ($answer instanceof TextAnswer) {
            $this->setTextAnswer($answer);
        }
    }
}
