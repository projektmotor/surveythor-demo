<?php

namespace PM\SurveythorBundle\Controller;

use PM\SurveythorBundle\Entity\Survey;
use PM\SurveythorBundle\Form\SurveyTitleType;
use PM\SurveythorBundle\Repository\SurveyRepository;
use QafooLabs\MVC\FormRequest;
use QafooLabs\MVC\RedirectRoute;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\JsonResponse;

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
     * @param FormFactory      $formFactory
     * @param Router           $router
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
     * @return array
     */
    public function indexAction()
    {
        return array(
            'surveys' => $this->surveyRepository->findAll(),
        );
    }

    /**
     * @param Survey $survey
     *
     * @return array
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
                    ),
                )
            )->createView(),
        );
    }

    /**
     * @param FormRequest $formRequest
     *
     * @return array|RedirectRoute
     */
    public function newAction(FormRequest $formRequest)
    {
        if (!$formRequest->handle(SurveyTitleType::class)) {
            return array(
                'survey' => false,
                'surveyTitleForm' => $formRequest->createFormView(),
            );
        }

        $survey = $formRequest->getValidData();
        $this->surveyRepository->save($survey);

        return new RedirectRoute(
            'survey_edit', array(
                'id' => $survey->getId(),
            )
        );
    }

    /**
     * @param FormRequest $formRequest
     * @param Survey      $survey
     *
     * @return JsonResponse
     */
    public function updateTitleAction(FormRequest $formRequest, Survey $survey)
    {
        if (!$formRequest->handle(SurveyTitleType::class, $survey)) {
            return new JsonResponse(
                [
                    'status' => 'INVALID',
                ]
            );
        }

        $survey = $formRequest->getValidData();
        $this->surveyRepository->save($survey);

        return new JsonResponse(
            [
                'status' => 'OK',
            ]
        );
    }

    /**
     * @param Survey $survey
     *
     * @return array
     */
    public function evaluationsAction(Survey $survey)
    {
        return [
            'survey' => $survey,
            'users' => [],
        ];
    }

    /**
     * @return RedirectRoute
     */
    public function evaluationsLastAction()
    {
        $surveys = $this->surveyRepository->findAll();
        $lastSurvey = $surveys[count($surveys) - 1];

        return new RedirectRoute('survey_evaluations', ['survey' => $lastSurvey->getId()]);
    }
}
