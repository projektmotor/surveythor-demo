<?php
namespace PM\SurveythorBundle\Entity;

use PM\SurveythorBundle\Entity\Choice;

/**
 * SingleChoiceAnswer
 */
class SingleChoiceAnswer extends Answer
{
    /**
     * @var Choice
     */
    private $choice;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get choice.
     *
     * @return choice.
     */
    public function getChoice()
    {
        return $this->choice;
    }

    /**
     * Set choice.
     *
     * @param choice the value to set.
     */
    public function setChoice(Choice $choice)
    {
        $this->choice = $choice;
    }
}
