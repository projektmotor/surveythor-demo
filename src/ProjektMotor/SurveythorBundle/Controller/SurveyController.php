<?php
namespace PM\SurveythorBundle\Controller;

use QafooLabs\MVC\FormRequest;
use QafooLabs\MVC\RedirectRoute;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use PM\SurveythorBundle\Repository\SurveyRepository;
use PM\SurveythorBundle\Entity\Survey;
use PM\SurveythorBundle\Factory\SurveyFactory;
use PM\SurveythorBundle\Form\SurveyType;
use PM\SurveythorBundle\Form\QuestionType;

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

    /**
     * __construct
     *
     * @param SurveyRepository $surveyRepository
     * @param SurveyFactory $surveyFactory
     */
    public function __construct(
        SurveyRepository $surveyRepository,
        SurveyFactory $surveyFactory
    ) {
        $this->surveyRepository = $surveyRepository;
        $this->surveyFactory = $surveyFactory;
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
