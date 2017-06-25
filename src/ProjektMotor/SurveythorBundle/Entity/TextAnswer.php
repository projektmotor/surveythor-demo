<?php

namespace PM\SurveythorBundle\Entity;

/**
 * TextAnswer
 */
class TextAnswer extends Answer
{
    /**
     * @var string
     */
    private $value;

    /**
     * Set value
     *
     * @param string $value
     *
     * @return TextAnswer
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}
