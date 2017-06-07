<?php
namespace PM\SurveythorBundle\Controller;

use QafooLabs\MVC\FormRequest;
use PM\SurveythorBundle\Entity\Survey;
use PM\SurveythorBundle\Entity\Result;
use PM\SurveythorBundle\Entity\ResultAnswer;
use PM\SurveythorBundle\Repository\SurveyRepository;
use PM\SurveythorBundle\Form\ResultType;

/**
 * ResultController
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class ResultController
{
    /**
     * @var SurveyRepository
     */
    private $surveyRepository;


    /**
     * __construct
     *
     * @param SurveyRepository $surveyRepository
     */
    public function __construct(
        SurveyRepository $surveyRepository
    ) {
        $this->surveyRepository = $surveyRepository;
    }

    public function newAction(FormRequest $formRequest, Survey $survey)
    {
        $result = new Result();
        foreach ($survey->getQuestions() as $question) {
            $resultAnswer = new ResultAnswer();
            $resultAnswer->setQuestion($question);
            $result->addResultAnswer($resultAnswer);
        }

        if (!$formRequest->handle(ResultType::class, $result)) {
            return array(
                'survey' => $survey,
                'form' => $formRequest->createFormView()
            );
        }

        dump($formRequest->getValidData());
        die();
    }
}
