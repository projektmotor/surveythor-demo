<?php
namespace PM\SurveythorBundle\Controller;

use QafooLabs\MVC\FormRequest;
use PM\SurveythorBundle\Form\SurveyType;
use PM\SurveythorBundle\Form\QuestionType;
use PM\SurveythorBundle\Repository\SurveyRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use PM\SurveythorBundle\Factory\SurveyFactory;
use QafooLabs\Bundle\NoFrameworkBundle\Request\SymfonyFormRequest;
use Symfony\Component\Form\FormFactory;
use PM\SurveythorBundle\Entity\Survey;

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
     * @var formFactory
     */
    private $formFactory;

    /**
     * @var surveyFactory
     */
    private $surveyFactory;

    public function __construct(
        SurveyRepository $surveyRepository,
        SurveyFactory $surveyFactory,
        FormFactory $formFactory
    ) {
        $this->surveyRepository = $surveyRepository;
        $this->surveyFactory = $surveyFactory;
        $this->formFactory = $formFactory;
    }

    /**
     * newAction
     *
     * @param FormRequest $formRequest
     */
    public function newAction(FormRequest $formRequest, Request $request)
    {
        if (!$formRequest->handle(SurveyType::class, new Survey())) {
            return array(
                'form' => $formRequest->createFormView()
            );
        }
        if ($request->isXmlHttpRequest()) {
            return array(
                'form' => $formRequest->createFormView()
            );
        } else {
            $survey = $formRequest->getValidData();
            dump($survey);
            die();
            $this->surveyRepository->save($survey);

            return new Response('thanks brother/sister');
        }
    }
}
