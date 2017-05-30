<?php
namespace PM\SurveythorBundle\Factory;

use PM\SurveythorBundle\Entity\Survey;

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

        foreach ($dtoSurvey['questions'] as $question) {
            $survey->addQuestion($question);
        }

        return $survey;
    }
}
