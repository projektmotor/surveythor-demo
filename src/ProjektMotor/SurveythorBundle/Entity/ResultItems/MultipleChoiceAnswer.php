<?php
namespace PM\SurveythorBundle\Entity\ResultItems;

use Doctrine\Common\Collections\ArrayCollection;
use PM\SurveythorBundle\Entity\SurveyItems\Question;
use PM\SurveythorBundle\Entity\Choice;
use PM\SurveythorBundle\Entity\ResultItem;

/**
 * MultipleChoiceAnswer
 */
class MultipleChoiceAnswer
{
    /**
     * @var int $id
     */
    private $id;

    /**
     * @var Choice[]|ArrayCollection
     */
    private $choices;

    private $question;

    private $resultItem;

    public function __construct()
    {
        $this->choices = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return id.
     */
    public function getId()
    {
        return $this->id;
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
        if ($this->choices->contains($choice)) {
            $this->choices->remove($choice);
        }
    }

    /**
     * Get question.
     *
     * @return question.
     */
    public function getQuestion()
    {
        return $this->question;
    }
    
    /**
     * Set question.
     *
     * @param question the value to set.
     */
    public function setQuestion($question)
    {
        $this->question = $question;
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

    public function clearChoices()
    {
        $this->choices = new ArrayCollection();
    }
}
