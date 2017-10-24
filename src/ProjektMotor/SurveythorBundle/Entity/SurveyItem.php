<?php
namespace PM\SurveythorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PM\SurveythorBundle\Entity\SurveyItems\ItemGroup;
use PM\SurveythorBundle\Entity\SurveyItems\Question;
use PM\SurveythorBundle\Entity\SurveyItems\SurveyTextItem;

/**
 * SurveyItem
 */
abstract class SurveyItem
{
    const BACKEND_TITLE_LENGTH = 90;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var Survey
     */
    protected $survey;

    /**
     * @var int
     */
    protected $sortOrder;

    /**
     * @var Condition[]|ArrayCollection
     */
    protected $conditions;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var ResultItemTemplate
     */
    private $template;

    /**
     * @var bool
     */
    private $displayTitle = false;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var ItemGroup
     */
    protected $itemGroup;


    public function __construct()
    {
        $this->conditions = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Survey
     */
    public function getSurvey()
    {
        return $this->survey;
    }

    /**
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
     * @return int
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * @param int $sortOrder
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;
    }

    /**
     * @param Condition $condition
     *
     * @return SurveyItem
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
     * @return Condition[]|ArrayCollection
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * @return string|null
     */
    public function getContentTypeName()
    {
        if ($this instanceof Question) {
            if ($this->getType() == 'mc') {
                return 'Multiple Choice Frage';
            } elseif ($this->getType() == 'text') {
                return 'Freitext Frage';
            } else {
                return 'Single Choice Frage';
            }
        }
        if ($this instanceof ItemGroup) {
            return sprintf(
                'Gruppe von %s Umfrageelementen',
                $this->getSurveyItems()->count()
            );
        }
        if ($this instanceof SurveyTextItem) {
            return 'Textelement';
        }
    }

    /**
     * @return string|null
     */
    public function getItemType()
    {
        if ($this instanceof Question) {
            if ($this->getType() == 'mc') {
                return 'multipleChoiceQuestion';
            } elseif ($this->getType() == 'text') {
                return 'textQuestion';
            } else {
                return 'singleChoiceQuestion';
            }
        }
        if ($this instanceof ItemGroup) {
            return 'itemGroup';
        }
        if ($this instanceof SurveyTextItem) {
            return 'textItem';
        }
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
     * @return string|null
     */
    public function getBackendTitle()
    {
        if (!is_null($this->title)) {
            return $this->title;
        }

        if ($this instanceof Question || $this instanceof SurveyTextItem) {
            if (strlen($this->getText()) < self::BACKEND_TITLE_LENGTH) {
                return $this->getText();
            } else {
                return sprintf(
                    '%s ...',
                    substr($this->getText(), 0, self::BACKEND_TITLE_LENGTH)
                );
            }
        }
    }

    /**
     * @return ResultItemTemplate
     */
    public function getTemplate()
    {
        return $this->template;
    }
    
    /**
     * @param ResultItemTemplate $template
     */
    public function setTemplate(ResultItemTemplate $template)
    {
        $this->template = $template;
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
     * @return ItemGroup
     */
    public function getItemGroup()
    {
        return $this->itemGroup;
    }
    
    /**
     * @param ItemGroup $itemGroup
     */
    public function setItemGroup(ItemGroup $itemGroup)
    {
        $this->itemGroup = $itemGroup;
    }

    public function setInitialSortOrder()
    {
        if (is_null($this->sortOrder)) {
            if (is_null($this->itemGroup)) {
                $this->sortOrder = $this->survey->getSurveyItems()->count();
            } else {
                $this->sortOrder = $this->itemGroup->getSurveyItems()->count();
            }
        }
    }

    /**
     * @return boolean
     */
    public function isParent()
    {
        return is_null($this->itemGroup);
    }

    /**
     * @return SurveyItem
     */
    public function getRoot()
    {
        if (is_null($this->itemGroup)) {
            return $this;
        } else {
            return $this->itemGroup->getRoot();
        }
    }
}
