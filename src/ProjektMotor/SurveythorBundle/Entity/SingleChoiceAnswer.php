<?php
namespace PM\SurveythorBundle\Entity;

/**
 * SingleChoiceAnswer
 */
class SingleChoiceAnswer extends Answer
{
    /**
     * @var Choice
     */
    private $choice;

    /**
     * @return Choice
     */
    public function getChoice()
    {
        return $this->choice;
    }

    /**
     * @param Choice $choice
     */
    public function setChoice(Choice $choice)
    {
        $this->choice = $choice;
    }
}
