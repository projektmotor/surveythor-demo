<?php
namespace PM\SurveythorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PM\SurveythorBundle\Entity\Answer;

/**
 * ResultAnswerMultipleChoice
 */
class ResultAnswerMultipleChoice extends ResultAnswer
{
    /**
     * @var ArrayCollection
     */
    private $answers;

    public function __construct()
    {
        parent::__construct();
        $this->answers = new ArrayCollection();
    }

    /**
     * Get answers.
     *
     * @return answers.
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer)
    {
        if (!$this->answers->contains($answer)) {
            $this->answers->add($answer);
        }
    }

    public function removeAnswer(Answer $answer)
    {
        $this->answers->remove($answer);
    }
}
