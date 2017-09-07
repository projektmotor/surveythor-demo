<?php
namespace PM\SurveythorBundle\Entity\SurveyItems;

use PM\SurveythorBundle\Entity\SurveyItem;

/**
 * TextItem
 */
class TextItem
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $text;

    /**
     * @var SurveyItem
     */
    private $surveyItem;

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
     * Set text
     *
     * @param string $text
     *
     * @return TextItem
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return SurveyItem
     */
    public function getSurveyItem()
    {
        return $this->surveyItem;
    }

    /**
     * @param SurveyItem
     */
    public function setSurveyItem(SurveyItem $surveyItem)
    {
        $this->surveyItem = $surveyItem;
    }
}
