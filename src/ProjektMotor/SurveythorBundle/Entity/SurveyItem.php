<?php
namespace PM\SurveythorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PM\SurveythorBundle\Entity\Survey;
use PM\SurveythorBundle\Entity\Condition;
use PM\SurveythorBundle\Entity\SurveyItems\Question;
use PM\SurveythorBundle\Entity\SurveyItems\TextItem;
use PM\SurveythorBundle\Entity\SurveyItems\ItemGroup;
use PM\SurveythorBundle\Entity\ResultItemTemplate;

/**
 * SurveyItem
 */
abstract class SurveyItem
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
     * @var string
     */
    protected $title;

    /**
     * @var ResultTemplate
     */
    private $template;

    /**
     * @var boolean
     */
    private $displayTitle = false;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var ItemGroup
     */
    private $itemGroup;


    public function __construct()
    {
        $this->conditions = new ArrayCollection();
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

    public function getContentTypeName()
    {
        if ($this instanceof Question) {
            if ($this->getType() == 'mc') {
                return 'Mutltiple Choice Frage';
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
        if ($this instanceof TextItem) {
            return 'Textelement';
        }
    }

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
        if ($this instanceof TextItem) {
            return 'textItem';
        }
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

    public function getBackendTitle()
    {
        if (!is_null($this->title)) {
            return $this->title;
        }

        if ($this instanceof Question) {
            if (strlen($this->getText()) < 90) {
                return $this->getText();
            } else {
                return sprintf(
                    '%s ...',
                    substr($this->getText(), 0, 90)
                );
            }
        }
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
    
    /**
     * Get description.
     *
     * @return description.
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * Set description.
     *
     * @param description the value to set.
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
    
    /**
     * Get itemGroup.
     *
     * @return itemGroup.
     */
    public function getItemGroup()
    {
        return $this->itemGroup;
    }
    
    /**
     * Set itemGroup.
     *
     * @param itemGroup the value to set.
     */
    public function setItemGroup($itemGroup)
    {
        $this->itemGroup = $itemGroup;
    }
}
