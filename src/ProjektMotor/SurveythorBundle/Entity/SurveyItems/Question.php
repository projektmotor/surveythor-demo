<?php

namespace PM\SurveythorBundle\Entity\SurveyItems;

use Doctrine\Common\Collections\ArrayCollection;
use PM\SurveythorBundle\Entity\Answer;
use PM\SurveythorBundle\Entity\Choice;
use PM\SurveythorBundle\Entity\QuestionTemplate;
use PM\SurveythorBundle\Entity\ResultItem;
use PM\SurveythorBundle\Entity\ResultItems\MultipleChoiceAnswer;
use PM\SurveythorBundle\Entity\ResultItems\SingleChoiceAnswer;
use PM\SurveythorBundle\Entity\ResultItems\TextAnswer;
use PM\SurveythorBundle\Entity\SurveyItem;

/**
 * Question
 */
class Question extends SurveyItem
{
    /**
     * @var string
     */
    private $text;

    /**
     * @var Choice[]|ArrayCollection
     */
    private $choices;

    /**
     * @var QuestionTemplate
     */
    private $questionTemplate;

    /**
     * @var string
     */
    private $type;

    public function __construct()
    {
        $this->choices = new ArrayCollection();

        parent::__construct();
    }

    /**
     * @param string $text
     *
     * @return Question
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param Choice $choice
     *
     * @return Question
     */
    public function addChoice(Choice $choice)
    {
        if (!$this->choices->contains($choice)) {
            $this->choices->add($choice);
            $choice->setQuestion($this);
        }

        return $this;
    }

    /**
     * @param Choice $choice
     */
    public function removeChoice(Choice $choice)
    {
        $this->choices->removeElement($choice);
    }

    /**
     * @return Choice[]|ArrayCollection
     */
    public function getChoices()
    {
        return $this->choices;
    }

    /**
     * Evaluates to true if question is multiple or single choice.
     *
     * @return bool
     */
    public function isChoiceQuestion()
    {
        return $this->getType() === 'mc' || $this->getType() === 'sc';
    }

    /**
     * @return bool
     */
    public function hasChoices()
    {
        return $this->choices->count() > 0;
    }

    /**
     * @return int
     */
    public function getMaxPoints()
    {
        $points = 0;
        if ($this->hasChoices()) {
            foreach ($this->getChoices() as $choice) {
                $points = $points + $choice->getMaxPoints();
            }
        }

        return $points;
    }

    /**
     * @return QuestionTemplate
     */
    public function getQuestionTemplate()
    {
        return $this->questionTemplate;
    }

    /**
     * @param QuestionTemplate $questionTemplate
     */
    public function setQuestionTemplate(QuestionTemplate $questionTemplate)
    {
        $this->questionTemplate = $questionTemplate;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return ResultItem
     * @throws \Exception
     */
    public function createResultItem()
    {
        $resultItem = new ResultItem();

        switch ($this->getType()) {
            case 'mc':
                $answer = new MultipleChoiceAnswer();
                break;
            case 'sc':
                $answer = new SingleChoiceAnswer();
                break;
            case 'text':
                $answer = new TextAnswer();
                break;
            default:
                throw new \Exception('a question has to have a type');
                break;
        }

        $answer->setQuestion($this);

        $resultItem->setAnswer($answer);
        $resultItem->setSurveyItem($this);

        return $resultItem;
    }
}
