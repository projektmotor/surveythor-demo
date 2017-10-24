<?php
namespace PM\SurveythorBundle\Entity\ResultItems;

use PM\SurveythorBundle\Entity\ResultItem;

/**
 * ResultTextItem
 */
class ResultTextItem
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
     * @var ResultItem
     */
    private $resultItem;

    /**
     * @param \PM\SurveythorBundle\Entity\SurveyItems\SurveyTextItem $surveyTextItem
     *
     * @return ResultTextItem
     */
    public static function createBySurveyTextItem(\PM\SurveythorBundle\Entity\SurveyItems\SurveyTextItem $surveyTextItem
    ) {
        $resultTextItem = new self();
        $resultTextItem->setText($surveyTextItem->getText());

        return $resultTextItem;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $text
     *
     * @return ResultTextItem
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
    public function getResultItem()
    {
        return $this->resultItem;
    }

    /**
     * @param ResultItem $resultItem
     */
    public function setResultItem(ResultItem $resultItem)
    {
        $this->resultItem = $resultItem;
    }
}
