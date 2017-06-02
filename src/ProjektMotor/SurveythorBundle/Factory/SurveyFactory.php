<?php
namespace PM\SurveythorBundle\Factory;

use PM\SurveythorBundle\Entity\Survey;
use PM\SurveythorBundle\Entity\Question;
use PM\SurveythorBundle\Entity\Answer;

/**
 * SurveyFactory
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class SurveyFactory
{
    public function createByDtoSurvey($dtoSurvey)
    {
        $survey = new Survey();

        $survey->setTitle($dtoSurvey['title']);

        foreach ($dtoSurvey['questions'] as $dtoQuestion) {
            $question = new Question();
            $question->setText($dtoQuestion['text']);

            foreach ($dtoQuestion['answers'] as $dtoAnswer) {
                $answer = new Answer();
                $answer->setText($dtoAnswer['text']);
                $answer->setPoints($dtoAnswer['points']);

                foreach ($dtoAnswer['childQuestions'] as $dtoChildQuestion) {
                    $childQuestion = new Question();
                    $childQuestion->setText($dtoChildQuestion['text']);

                    foreach ($dtoChildQuestion['answers'] as $dtoChildQuestionAnswer) {
                        $childQuestionAnswer = new Answer();
                        $childQuestionAnswer->setText($dtoChildQuestionAnswer['text']);
                        $childQuestionAnswer->setPoints($dtoChildQuestionAnswer['points']);

                        $childQuestion->addAnswer($childQuestionAnswer);
                    }

                    $answer->addChildQuestion($childQuestion);
                }

                $question->addAnswer($answer);
            }

            $survey->addQuestion($question);
        }

        return $survey;
    }
}
