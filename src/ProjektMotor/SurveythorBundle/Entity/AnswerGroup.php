<?php
namespace PM\SurveythorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PM\SurveythorBundle\Entity\Answer;
use PM\SurveythorBundle\Entity\AnswerGroup;
use PM\SurveythorBundle\Entity\Result;

/**
 * AnswerGroup
 */
class AnswerGroup
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var Answer[]
     */
    private $answers;

    /**
     * @var Result
     */
    private $result;

    /**
     * @var string
     */
    private $header;

    /**
     * @var AnswerGroup[]
     */
    private $childGroups;

    /**
     * @var AnswerGroup
     */
    private $parentGroup;


    public function __construct()
    {
        $this->answers = new ArrayCollection();
        $this->childGroups = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get answers.
     *
     * @return Answer[]|ArrayCollection answers.
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * @param Answer $answer
     */
    public function addAnswer(Answer $answer)
    {
        if (!$this->answers->contains($answer)) {
            $this->answers->add($answer);
        }
    }

    /**
     * @param Answer $answer
     */
    public function removeAnswer(Answer $answer)
    {
        $this->answers->remove($answer);
    }

    public function getResult()
    {
        return $this->result;
    }

    public function setResult($result)
    {
        $this->result = $result;
    }
    
    /**
     * Get header.
     *
     * @return header.
     */
    public function getHeader()
    {
        return $this->header;
    }
    
    /**
     * Set header.
     *
     * @param header the value to set.
     */
    public function setHeader($header)
    {
        $this->header = $header;
    }

    /**
     * @param AnswerGroup $childGroup
     *
     * @return AnswerGroup
     */
    public function addChildGroup(AnswerGroup $answerGroup)
    {
        if (!$this->childGroups->contains($answerGroup)) {
            $this->childGroups->add($answerGroup);
        }

        return $this;
    }

    /**
     * @param AnswerGroup $answerGroup
     */
    public function removeChildGroup(AnswerGroup $answerGroup)
    {
        $this->childGroups->removeElement($answerGroup);
    }

    /**
     * @return AnswerGroup[]|ArrayCollection
     */
    public function getChildGroups()
    {
        return $this->childGroups;
    }
    
    /**
     * Get parentGroup.
     *
     * @return parentGroup.
     */
    public function getParentGroup()
    {
        return $this->parentGroup;
    }
    
    /**
     * Set parentGroup.
     *
     * @param parentGroup the value to set.
     */
    public function setParentGroup($parentGroup)
    {
        $this->parentGroup = $parentGroup;
    }
}
