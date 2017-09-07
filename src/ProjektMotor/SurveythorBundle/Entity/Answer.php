<?php
namespace PM\SurveythorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PM\SurveythorBundle\Entity\ResultItems\SingleChoiceAnswer;
use PM\SurveythorBundle\Entity\ResultItems\MultipleChoiceAnswer;
use PM\SurveythorBundle\Entity\ResultItems\TextAnswer;

/**
 * Answer
 */
abstract class Answer
{
    /**
     * @param Question $question
     *
     * @return MultipleChoiceAnswer|SingleChoiceAnswer|TextAnswer
     * @throws \Exception
     */
    public static function createByQuestionType($question)
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

        return $answer;
    }
}
