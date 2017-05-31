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

                $question->addAnswer($answer);
            }

            $survey->addQuestion($question);
        }

        return $survey;
    }
}
