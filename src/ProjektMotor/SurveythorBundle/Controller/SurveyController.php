<?php
namespace PM\SurveythorBundle\Controller;

use QafooLabs\MVC\FormRequest;
use PM\SurveythorBundle\Form\SurveyType;
use PM\SurveythorBundle\Repository\SurveyRepository;
use Symfony\Component\HttpFoundation\Response;
use PM\SurveythorBundle\Factory\SurveyFactory;

/**
 * SurveyController
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class SurveyController
{
    /**
     * @var SurveyRepository
     */
    private $surveyRepository;

    /**
     * @var surveyFactory
     */
    private $surveyFactory;

    public function __construct(
        SurveyRepository $surveyRepository,
        SurveyFactory $surveyFactory
    ) {
        $this->surveyRepository = $surveyRepository;
        $this->surveyFactory = $surveyFactory;
    }

    /**
     * newAction
     *
     * @param FormRequest $formRequest
     */
    public function newAction(FormRequest $formRequest)
    {
        if (!$formRequest->handle(SurveyType::class)) {
            return array(
                'form' => $formRequest->createFormView()
            );
        }

        $survey = $this->surveyFactory->createByDtoSurvey($formRequest->getValidData());
        $this->surveyRepository->save($survey);

        return new Response('thanks brother/sister');
    }
}
