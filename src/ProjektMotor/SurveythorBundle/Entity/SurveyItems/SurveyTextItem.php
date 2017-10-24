<?php

namespace PM\SurveythorBundle\Entity\SurveyItems;

use PM\SurveythorBundle\Entity\ResultItem;
use PM\SurveythorBundle\Entity\ResultItems\ResultTextItem;
use PM\SurveythorBundle\Entity\SurveyItem;

/**
 * SurveyTextItem
 */
class SurveyTextItem extends SurveyItem
{
    /**
     * @var string
     */
    private $text;

    /**
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
     * @return SurveyTextItem
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return ResultItem
     */
    public function createResultItem()
    {
        $resultItem = new ResultItem();

        $resultTextItem = new ResultTextItem();
        $resultTextItem->setText($this->getText());
        $resultItem->setResultTextItem($resultTextItem);
        $resultItem->setSurveyItem($this);

        return $resultItem;
    }
}
