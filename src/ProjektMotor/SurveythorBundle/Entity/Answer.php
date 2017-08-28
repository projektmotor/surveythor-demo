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
}
