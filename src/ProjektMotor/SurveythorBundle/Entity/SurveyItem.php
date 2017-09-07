<?php
namespace PM\SurveythorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PM\SurveythorBundle\Entity\Survey;
use PM\SurveythorBundle\Entity\Condition;
use PM\SurveythorBundle\Entity\SurveyItems\Question;
use PM\SurveythorBundle\Entity\SurveyItems\TextItem;

/**
 * SurveyItem
 */
class SurveyItem
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var Survey
     */
    protected $survey;

    /**
     * @var integer
     */
    protected $sortOrder;

    /**
     * @var Condition[]|Arraycollection
     */
    protected $conditions;

    /**
     * @var Question[]
     */
    protected $question;

    /**
     * @var SurveyItem[]
     */
    protected $childItems;

    /**
     * @var SurveyItem
     */
    protected $parentItem;

    /**
     * @var TextItem
     */
    protected $textItem;

    protected $title;

    private $template;

    /**
     * @var boolean
     */
    private $displayTitle = true;

    public function __construct()
    {
        $this->conditions = new ArrayCollection();
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
     * Get survey.
     *
     * @return Survey
     */
    public function getSurvey()
    {
        return $this->survey;
    }

    /**
     * Set survey.
     *
     * @param Survey $survey
     *
     * @return SurveyItem
     */
    public function setSurvey(Survey $survey)
    {
        $this->survey = $survey;

        return $this;
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

    public function setInitialSortOrder()
    {
        if (null !== $this->survey) {
            $this->setSortOrder($this->survey->getSurveyItems()->count());
            return $this;
        }

        // dis is needed for fixture loading, should never happen
        if (null !== $this->sortOrder) {
            return $this;
        }

        throw new \Exception('a question has to have a survey or a parent choice');
    }

    /**
     * @param Condition $condition
     */
    public function addCondition(Condition $condition)
    {
        if (!$this->conditions->contains($condition)) {
            $this->conditions->add($condition);
            $condition->setSurveyItem($this);
        }

        return $this;
    }

    /**
     * @param Condition $condition
     */
    public function removeCondition(Condition $condition)
    {
        $this->conditions->removeElement($condition);
    }

    /**
     * Get conditions.
     *
     * @return Condition[]|ArrayCollection
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * Get questions.
     *
     * @return Question|null
     */
    public function getQuestion()
    {
        return $this->question;
    }

    public function setQuestion(Question $question)
    {
        $this->question = $question;
    }

    /**
     * @return TextItem|null
     */
    public function getTextItem()
    {
        return $this->textItem;
    }

    /**
     * @param TextItem $textItem
     * @return SurveyItem
     */
    public function setTextItem(TextItem $textItem)
    {
        $this->textItem = $textItem;

        return $this;
    }

    public function getContent()
    {
        if (!is_null($this->question)) {
            return $this->question;
        }
        if ($this->childItems->count() > 0) {
            return $this->childItems;
        }
        if (!is_null($this->textItem)) {
            return $this->textItem;
        }
    }

    public function getContentTypeName()
    {

        if (!is_null($this->question)) {
            if ($this->question->getType() == 'mc') {
                return 'Mutltiple Choice Frage';
            } else {
                return 'Single Choice Frage';
            }
        }
        if ($this->childItems->count() > 0) {
            return sprintf(
                'Gruppe von %s Umfrageelementen',
                $this->childItems->count()
            );
        }
        if (!is_null($this->textItem)) {
            return 'Textelement';
        }
    }

    public function getParentItem()
    {
        return $this->parentItem;
    }

    public function setParentItem(SurveyItem $parentItem)
    {
        $this->parentItem = $parentItem;
    }

    /**
     * @param SurveyItem $childItem
     */
    public function addChildItem(SurveyItem $childItem)
    {
        if (!$this->childItems->contains($childItem)) {
            $this->childItems->add($childItem);
        }

        return $this;
    }

    /**
     * @param SurveyItem $childItem
     */
    public function removeChildItem(SurveyItem $childItem)
    {
        $this->childItems->removeElement($childItem);
    }

    /**
     * Get childItems.
     *
     * @return SurveyItem[]|ArrayCollection
     */
    public function getChildItems()
    {
        return $this->childItems;
    }
    
    /**
     * Get title.
     *
     * @return title.
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Set title.
     *
     * @param title the value to set.
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
    
    /**
     * Get template.
     *
     * @return template.
     */
    public function getTemplate()
    {
        return $this->template;
    }
    
    /**
     * Set template.
     *
     * @param template the value to set.
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }
    
    /**
     * Get displayTitle.
     *
     * @return displayTitle.
     */
    public function getDisplayTitle()
    {
        return $this->displayTitle;
    }

    /**
     * Set displayTitle.
     *
     * @param displayTitle the value to set.
     */
    public function setDisplayTitle($displayTitle)
    {
        $this->displayTitle = $displayTitle;
    }
}
