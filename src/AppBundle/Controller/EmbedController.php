<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Survey;
use AppBundle\Repository\SurveyRepository;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\FormFactory;

class EmbedController
{
    /** @var SurveyController */
    private $surveyController;
    private $resultController;

    public function __construct(SurveyController $surveyController, ResultController $resultController)
    {
        $this->surveyController = $surveyController;
        $this->resultController = $resultController;
    }

    public function executeSurveyAction(Survey $survey): array
    {
        return $this->resultController->newAction($survey);
    }


    public function editSurveyAction(Survey $survey): array
    {

        return $this->surveyController->editAction($survey);
    }

    public function evaluateSurveyAction(Survey $survey)
    {
        return $this->surveyController->evaluationsAction($survey);

    }
}