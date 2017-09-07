<?php
namespace PM\SurveythorBundle\Entity\ResultItems;

use PM\SurveythorBundle\Entity\ResultItem;

/**
 * TextItem
 */
class TextItem
{
    private $id;
    /**
     * @var string
     */
    private $text;

    private $resultItem;

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
     * Get resultItem.
     *
     * @return resultItem.
     */
    public function getResultItem()
    {
        return $this->resultItem;
    }
    
    /**
     * Set resultItem.
     *
     * @param resultItem the value to set.
     */
    public function setResultItem($resultItem)
    {
        $this->resultItem = $resultItem;
    }
}
