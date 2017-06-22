<?php

namespace PM\SurveythorBundle\Entity;

/**
 * ResultAnswerText
 */
class ResultAnswerText extends ResultAnswer
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
     * @return ResultAnswerText
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
