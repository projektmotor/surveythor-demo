<?php
namespace PM\SurveythorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Answer
 */
abstract class Answer
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var Result
     */
    private $result;

    /**
     * @var Question
     */
    private $question;

    /**
     * @var Answer[]|ArrayCollection
     */
    private $childAnswers;

    /**
     * @var Answer|ArrayCollection
     */
    private $parentAnswer;

    /**
     * @var integer
     */
    private $position;


    public function __construct()
    {
        $this->childAnswers = new ArrayCollection();
    }

    /**
     * @param Question $question
     *
     * @return MultipleChoiceAnswer|SingleChoiceAnswer|TextAnswer
     * @throws \Exception
     */
    public static function createByQuestionType(Question $question)
    {
        switch ($question->getType()) {
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

        $answer->setQuestion($question);

        return $answer;
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
     * Get result.
     *
     * @return Result
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set result.
     *
     * @param Result $result
     */
    public function setResult(Result $result)
    {
        $this->result = $result;
    }

    /**
     * Get question.
     *
     * @return Question
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set question.
     *
     * @param Question $question
     */
    public function setQuestion(Question $question)
    {
        $this->question = $question;
    }

    /**
     * Get childAnswers.
     *
     * @return Answer[]|ArrayCollection childAnswers.
     */
    public function getChildAnswers()
    {
        return $this->childAnswers;
    }

    /**
     * @param Answer $childAnswer
     */
    public function addChildAnswer(Answer $childAnswer)
    {
        if (!$this->childAnswers->contains($childAnswer)) {
            $this->childAnswers->add($childAnswer);
            $childAnswer->setParentAnswer($this);
        }
    }

    /**
     * @param Answer $childAnswer
     */
    public function removeChildAnswer(Answer $childAnswer)
    {
        $this->childAnswers->remove($childAnswer);
    }

    /**
     * Get parentAnswer.
     *
     * @return Answer parentAnswer.
     */
    public function getParentAnswer()
    {
        return $this->parentAnswer;
    }

    /**
     * Set parentAnswer.
     *
     * @param Answer $parentAnswer the value to set.
     */
    public function setParentAnswer(Answer $parentAnswer)
    {
        $this->parentAnswer = $parentAnswer;
    }

    /**
     * Get position.
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set position.
     *
     * @param int $position the value to set.
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }
}
