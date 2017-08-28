<?php
namespace PM\SurveythorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PM\SurveythorBundle\Entity\Question;

/**
 * QuestionGroup
 */
class QuestionGroup extends SurveyItem
{
    /**
     * @var string
     */
    private $header;

    /**
     * @var Question[]
     */
    private $questions;

    /**
     * @var QuestionGroup
     */
    private $parentGroup;

    /**
     * @var QuestionGroup[]
     */
    private $childGroups;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->childGroups = new ArrayCollection();
    }

    /**
     * Set header
     *
     * @param string $header
     *
     * @return QuestionGroup
     */
    public function setHeader($header)
    {
        $this->header = $header;

        return $this;
    }

    /**
     * Get header
     *
     * @return string
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param Question $question
     *
     * @return Question
     */
    public function addQuestion(Question $question)
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
        }

        return $this;
    }

    /**
     * @param Question $question
     */
    public function removeQuestion(Question $question)
    {
        $this->questions->removeElement($question);
    }

    /**
     * Get questions.
     *
     * @return Question[]|ArrayCollection
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * @return QuestionGroup.
     */
    public function getParentGroup()
    {
        return $this->parentGroup;
    }

    /**
     * @param QuestionGroup
     */
    public function setParentGroup(QuestionGroup $parentGroup)
    {
        $this->parentGroup = $parentGroup;
    }

    /**
     * @param QuestionGroup $childGroup
     *
     * @return QuestionGroup
     */
    public function addChildGroup(QuestionGroup $questionGroup)
    {
        if (!$this->childGroups->contains($questionGroup)) {
            $this->childGroups->add($questionGroup);
        }

        return $this;
    }

    /**
     * @param QuestionGroup $questionGroup
     */
    public function removeChildGroup(QuestionGroup $questionGroup)
    {
        $this->childGroups->removeElement($questionGroup);
    }

    /**
     * @return QuestionGroup[]|ArrayCollection
     */
    public function getChildGroups()
    {
        return $this->childGroups;
    }
}
