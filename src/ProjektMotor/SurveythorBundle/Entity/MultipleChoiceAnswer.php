<?php
namespace PM\SurveythorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * MultipleChoiceAnswer
 */
class MultipleChoiceAnswer extends Answer
{
    /**
     * @var Choice[]|ArrayCollection
     */
    private $choices;

    public function __construct()
    {
        parent::__construct();
        $this->choices = new ArrayCollection();
    }

    /**
     * Get choices.
     *
     * @return Choice[]|ArrayCollection
     */
    public function getChoices()
    {
        return $this->choices;
    }

    /**
     * @param Choice $choice
     */
    public function addChoice(Choice $choice)
    {
        if (!$this->choices->contains($choice)) {
            $this->choices->add($choice);
        }
    }

    /**
     * @param Choice $choice
     */
    public function removeChoice(Choice $choice)
    {
        $this->choices->remove($choice);
    }
}
