<?php

namespace PM\SurveythorBundle\Entity;

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
