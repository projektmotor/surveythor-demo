<?php
namespace PM\SurveythorBundle\Controller;

use PM\SurveythorBundle\Entity\Survey;
use PM\SurveythorBundle\Form\SurveyType;
use PM\SurveythorBundle\Repository\SurveyRepository;
use QafooLabs\MVC\FormRequest;
use QafooLabs\MVC\RedirectRoute;
use Symfony\Component\HttpFoundation\Request;

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
     * @param SurveyRepository $surveyRepository
     */
    public function __construct(
        SurveyRepository $surveyRepository
    ) {
        $this->surveyRepository = $surveyRepository;
    }

    /**
     * indexAction
     */
    public function indexAction()
    {
        return array(
            'surveys' => $this->surveyRepository->findAll()
        );
    }

    /**
     * formAction
     *
     * @param FormRequest $formRequest
     * @param Request $request
     * @param Survey $survey
     *
     * @return array|RedirectRoute
     */
    public function formAction(FormRequest $formRequest, Request $request, Survey $survey = null)
    {
        $survey = null === $survey ? new Survey() : $survey;
        if (!$formRequest->handle(SurveyType::class, $survey)
            || $request->isXmlHttpRequest()
        ) {
            return array(
                'form' => $formRequest->createFormView()
            );
        }

        $survey = $formRequest->getValidData();
        $this->surveyRepository->save($survey);

        return new RedirectRoute('survey_index');
    }
}
