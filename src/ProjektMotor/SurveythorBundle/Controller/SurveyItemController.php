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
 * SurveyItemController
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class SurveyItemController
{
    public function newAction(FormRequest $formRequest, $type)
    {
        switch ($type) {
            case 'singleChoice':
                $item = new Question();
                $item->setType('sc');
                break;
            case 'multipleChoice':
                $item = new Question();
                $item->setType('mc');
                break;
            case 'textQuestion':
                $item = new Question();
                $item->setType('text');
                break;
            case 'textItem':
                $item = new TextItem();
                break;
            case 'itemGroup':
                $item = new ItemGroup();
                break;
        }
        $formRequest->handle(SurveyItemType::class, $item);

        return array(
            'form' => $formRequest->createFormView()
        );
    }
}
