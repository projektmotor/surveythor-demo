<?php
namespace PM\SurveythorBundle\Controller;

use QafooLabs\MVC\FormRequest;
use PM\SurveythorBundle\Entity\Survey;
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
        if (!$formRequest->handle(ResultType::class)) {
            return array(
                'survey' => $survey,
                'form' => $formRequest->createFormView()
            );
        }

        return array(
            'survey' => $survey,
            'form' => $formRequest->createFormView()
        );
    }
}
