<?php
namespace PM\SurveythorBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\FormFactory;
use QafooLabs\MVC\FormRequest;
use QafooLabs\MVC\RedirectRoute;
use AppBundle\Controller\UserController;
use PM\SurveythorBundle\Entity\Survey;
use PM\SurveythorBundle\Form\SurveyTitleType;
use PM\SurveythorBundle\Repository\SurveyRepository;

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
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @var Router
     */
    private $router;


    /**
     * @param SurveyRepository $surveyRepository
     */
    public function __construct(
        SurveyRepository $surveyRepository,
        FormFactory $formFactory,
        Router $router
    ) {
        $this->surveyRepository = $surveyRepository;
        $this->formFactory = $formFactory;
        $this->router = $router;
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
     * @param Survey $survey
     *
     * @return array|RedirectRoute
     */
    public function editAction(Survey $survey)
    {
        return array(
            'survey' => $survey,
            'surveyTitleForm' => $this->formFactory->create(
                SurveyTitleType::class,
                $survey,
                array(
                    'action' => $this->router->generate(
                        'survey_update_title',
                        array('survey' => $survey->getId())
                    )
                )
            )->createView()
        );
    }

    public function newAction(FormRequest $formRequest)
    {
        if (!$formRequest->handle(SurveyTitleType::class)) {
            return array(
                'survey' => false,
                'surveyTitleForm' => $formRequest->createFormView()
            );
        }

        $survey = $formRequest->getValidData();
        $this->surveyRepository->save($survey);

        return new RedirectRoute('survey_edit', array(
            'id' => $survey->getId()
        ));
    }

    public function updateTitleAction(FormRequest $formRequest, Survey $survey)
    {
        if (!$formRequest->handle(SurveyTitleType::class, $survey)) {
            return new JsonResponse(json_encode(array(
                'status' => 'INVALID'
            )));
        }

        $survey = $formRequest->getValidData();
        $this->surveyRepository->save($survey);

        return new JsonResponse(json_encode(array(
            'status' => 'OK'
        )));
    }

    public function evaluationsAction(Survey $survey)
    {
        return array(
            'survey' => $survey,
            'users' => UserController::$users
        );
    }
}
