<?php
namespace PM\SurveythorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PM\SurveythorBundle\Entity\Choice;

/**
 * MultipleChoiceAnswer
 */
class MultipleChoiceAnswer extends Answer
{
    /**
     * @var ArrayCollection
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
     * @return choices.
     */
    public function getChoices()
    {
        return $this->choices;
    }

    public function addChoice(Choice $choice)
    {
        if (!$this->choices->contains($choice)) {
            $this->choices->add($choice);
        }
    }

    public function removeChoice(Choice $choice)
    {
        $this->choices->remove($choice);
    }
}
