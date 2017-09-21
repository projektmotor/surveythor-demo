<?php
namespace PM\SurveythorBundle\Entity\SurveyItems;

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
}
