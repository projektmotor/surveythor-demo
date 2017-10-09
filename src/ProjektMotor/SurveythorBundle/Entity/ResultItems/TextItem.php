<?php
namespace PM\SurveythorBundle\Entity\ResultItems;

use PM\SurveythorBundle\Entity\ResultItem;

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
     * @var ResultItem
     */
    private $resultItem;

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
