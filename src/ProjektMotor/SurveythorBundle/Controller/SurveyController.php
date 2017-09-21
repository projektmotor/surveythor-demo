<?php
namespace PM\SurveythorBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Common\Collections\ArrayCollection;
use QafooLabs\MVC\FormRequest;
use QafooLabs\MVC\RedirectRoute;
use AppBundle\Controller\UserController;
use PM\SurveythorBundle\Entity\Survey;
use PM\SurveythorBundle\Entity\SurveyItems\Question;
use PM\SurveythorBundle\Entity\SurveyItems\TextItem;
use PM\SurveythorBundle\Entity\SurveyItems\ItemGroup;
use PM\SurveythorBundle\Entity\SurveyItem;
use PM\SurveythorBundle\Form\SurveyItems\QuestionType;
use PM\SurveythorBundle\Form\SurveyItems\TextItemType;
use PM\SurveythorBundle\Form\SurveyItems\ItemGroupType;
use PM\SurveythorBundle\Form\SurveyType;
use PM\SurveythorBundle\Form\SurveyItemType;
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
    public function editAction(FormRequest $formRequest, Request $request, Survey $survey = null)
    {
        $survey = null === $survey ? new Survey() : $survey;

        return [ 'survey' => $survey ];
    }

    public function surveyItemFormAction(FormRequest $formRequest, SurveyItem $item)
    {
        switch (true) {
            case $item instanceof Question:
                $type = QuestionType::class;
                break;
            case $item instanceof TextItem:
                $type = TextItemType::class;
                break;
            case $item instanceof ItemGroup:
                $type = ItemGroupType::class;
                break;
        }

        $type = SurveyItemType::class;
        $formRequest->handle($type, $item);

        return array(
            'form' => $formRequest->createFormView()
        );
    }

    public function evaluationsAction(Survey $survey)
    {
        return array(
            'survey' => $survey,
            'users' => UserController::$users
        );
    }
}
