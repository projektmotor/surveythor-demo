<?php
namespace PM\SurveythorBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Common\Collections\ArrayCollection;
use PM\SurveythorBundle\Entity\Survey;
use PM\SurveythorBundle\Form\SurveyType;
use PM\SurveythorBundle\Repository\SurveyRepository;
use QafooLabs\MVC\FormRequest;
use QafooLabs\MVC\RedirectRoute;
use AppBundle\Controller\UserController;

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

        $originalItems = new ArrayCollection();
        foreach ($survey->getSurveyItems() as $item) {
            $originalItems->add($item);
        }

        if (!$formRequest->handle(SurveyType::class, $survey)
            || $request->isXmlHttpRequest()
        ) {
            return array(
                'survey' => $survey,
                'form' => $formRequest->createFormView()
            );
        }

        $survey = $formRequest->getValidData();

        foreach ($originalItems as $item) {
            if (false === $survey->getSurveyItems()->contains($item)) {
                $item->setSurvey(null);
            } else {
                if ($item->getConditions() !== null) {
                    foreach ($item->getConditions() as $condition) {
                        $condition->setItem($item);
                    }
                }
            }
        }

        $this->surveyRepository->save($survey);

        return new RedirectRoute('survey_index');
    }

    /**
     * @param FormRequest $formRequest
     * @param Request $request
     * @param Survey $survey
     *
     * @return array|RedirectRoute
     */
    public function saveAction(FormRequest $formRequest, Request $request, Survey $survey)
    {
        $survey = null === $survey ? new Survey() : $survey;

        if (!$request->isXmlHttpRequest()) {
            return false;
        }

        if (!$formRequest->handle(SurveyType::class, $survey)) {
            return new JsonResponse(array(
                'status' => 'NOT VALID'
            ));
        }

        $survey = $formRequest->getValidData();
        $this->surveyRepository->save($survey);

        return new JsonResponse(array(
            'status' => 'OK'
        ));
    }

    public function evaluationsAction(Survey $survey)
    {
        return array(
            'survey' => $survey,
            'users' => UserController::$users
        );
    }
}
