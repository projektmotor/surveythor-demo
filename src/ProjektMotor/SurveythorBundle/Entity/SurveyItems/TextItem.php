<?php

namespace PM\SurveythorBundle\Entity\SurveyItems;

use PM\SurveythorBundle\Entity\ResultItem;
use PM\SurveythorBundle\Entity\ResultItems\TextItem as ResultTextItem;
use PM\SurveythorBundle\Entity\SurveyItem;

/**
 * TextItem
 */
class TextItem extends SurveyItem
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
     * @return TextItem
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

        $textItem = new ResultTextItem();
        $textItem->setText($this->getText());
        $resultItem->setTextItem($textItem);
        $resultItem->setSurveyItem($this);

        return $resultItem;
    }
}
