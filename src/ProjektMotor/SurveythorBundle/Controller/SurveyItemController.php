<?php
namespace PM\SurveythorBundle\Controller;

use PM\SurveythorBundle\Entity\Factory\SurveyItemFactory;
use PM\SurveythorBundle\Entity\Survey;
use PM\SurveythorBundle\Entity\SurveyItem;
use PM\SurveythorBundle\Entity\SurveyItems\Question;
use PM\SurveythorBundle\Entity\SurveyItems\TextItem;
use PM\SurveythorBundle\Entity\SurveyItems\ItemGroup;
use PM\SurveythorBundle\Form\SurveyItemType;
use PM\SurveythorBundle\Repository\ConditionRepository;
use PM\SurveythorBundle\Repository\SurveyItemRepository;
use QafooLabs\MVC\FormRequest;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Twig_Environment;

/**
 * SurveyItemController
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class SurveyItemController
{
    /**
     * @var ConditionRepository
     */
    private $conditionRepository;

    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var SurveyItemRepository
     */
    private $surveyItemRepository;

    /**
     * @param SurveyItemRepository $surveyItemRepository
     * @param ConditionRepository  $conditionRepository
     * @param FormFactory          $formFactory
     * @param Twig_Environment     $twig
     * @param Router               $router
     */
    public function __construct(
        SurveyItemRepository $surveyItemRepository,
        ConditionRepository $conditionRepository,
        FormFactory $formFactory,
        Twig_Environment $twig,
        Router $router
    ) {
        $this->surveyItemRepository = $surveyItemRepository;
        $this->conditionRepository = $conditionRepository;
        $this->formFactory = $formFactory;
        $this->twig = $twig;
        $this->router = $router;
    }

    /**
     * @param FormRequest $formRequest
     * @param SurveyItem  $item
     *
     * @return JsonResponse
     */
    public function updateAction(FormRequest $formRequest, SurveyItem $item)
    {
        if (!$formRequest->handle(
            SurveyItemType::class,
            $item,
            array('action' => $this->router->generate(
                'surveyitem_update',
                array('item' => $item->getId())
            ))
        )) {
            return new JsonResponse(json_encode(array(
                'status' => 'NOT VALID',
                'open' => array($item->getId())
            )));
        }

        $item = $formRequest->getValidData();
        $this->surveyItemRepository->save($item);

        return new JsonResponse(json_encode(array(
            'status' => 'OK',
            'open' => array($item->getId())
        )));
    }

    /**
     * @param Survey $survey
     * @param string $type
     *
     * @return JsonResponse
     */
    public function newAction(Survey $survey, $type)
    {
        $item = SurveyItemFactory::createByType($type);
        $item->setSurvey($survey);
        $this->surveyItemRepository->save($item);

        $form = $this->formFactory->create(
            SurveyItemType::class,
            $item,
            array('action' => $this->router->generate(
                'surveyitem_update',
                array('item' => $item->getId())
            ))
        );
        $html = $this->twig->render(
            '@PMSurveythorBundle/SurveyItem/new.html.twig',
            array(
                'form'  => $form->createView()
            )
        );

        return new JsonResponse(json_encode(array(
            'html' => $html,
            'open' => array($item->getId()),
            'status' => 'OK'
        )));
    }

    /**
     * @param Request $request
     * @param Survey  $survey
     * @param string  $type
     *
     * @return JsonResponse
     */
    public function itemGroupAddItemAction(Request $request, Survey $survey, $type)
    {
        /** @var SurveyItem $parentItem */
        $parentItem = $this->surveyItemRepository->findOneById($request->query->get('parent'));
        $sortOrder = $request->query->get('sortorder');
        $item = SurveyItemFactory::createByType($type);

        foreach ($parentItem->getSurveyItems() as $surveyItem) {
            if ($surveyItem->getSortOrder() >= $sortOrder) {
                $surveyItem->setSortOrder($surveyItem->getSortOrder() + 1);
                $this->surveyItemRepository->save($surveyItem);
            }
        }

        $parentItem->addSurveyItem($item);
        $item->setSortOrder($sortOrder);
        $this->surveyItemRepository->save($parentItem);
        $this->surveyItemRepository->detach($parentItem);
        $parentItem = $this->surveyItemRepository->findOneById($request->query->get('parent'));

        $form = $this->formFactory->create(
            SurveyItemType::class,
            $parentItem->getRoot(),
            array('action' => $this->router->generate(
                'surveyitem_update',
                array('item' => $parentItem->getRoot()->getId())
            ))
        );

        $html = $this->twig->render(
            '@PMSurveythorBundle/SurveyItem/itemGroupAddItem.html.twig',
            array('form'  => $form->createView())
        );

        return new JsonResponse(json_encode(array(
            'html' => $html,
            'open' => array_merge(array($item->getId()), $parentItem->getGroupIdsFromTop()),
            'root' => $parentItem->getRoot()->getId(),
            'status' => 'OK'
        )));
    }

    /**
     * @param SurveyItem $item
     *
     * @return array
     */
    public function formAction(SurveyItem $item)
    {
         $form = $this->formFactory->create(
             SurveyItemType::class,
             $item,
             array('action' => $this->router->generate(
                 'surveyitem_update',
                 array('item' => $item->getId())
             ))
         );

         return array('form' => $form->createView());
    }

    /**
     * @param Request    $request
     * @param SurveyItem $item
     *
     * @return JsonResponse
     */
    public function setSortOrderAction(Request $request, SurveyItem $item)
    {
        $sortOrder = $request->query->get('sortorder');
        $item->setSortOrder($sortOrder);
        $this->surveyItemRepository->save($item);

        return new JsonResponse(json_encode(['status' => 'OK']));
    }

    /**
     * @param SurveyItem $item
     *
     * @return JsonResponse
     */
    public function removeAction(SurveyItem $item)
    {
        $conditions = $this->getItemConditions($item);
        if (empty(($conditions))) {
            $id = $item->getId();
            $this->surveyItemRepository->remove($item);
            return new JsonResponse(json_encode(array(
                'status' => 'OK',
                'item' => $id
            )));
        } else {
            return new JsonResponse(json_encode(array(
                'status' => 'FAIL',
                'reason' => 'Diese Frage kann nicht gelöscht werden, sie wird von mind. einer Bedingung verwendet.'
            )));
        }
    }

    private function getItemConditions(SurveyItem $item, $conditions = null)
    {
        $conditions = $conditions === null ? array() : $conditions;
        switch (get_class($item)) {
            case Question::class:
                $conditions = $this->conditionRepository->getConditionsByQuestion($item);
                break;
            case ItemGroup::class:
                foreach ($item->getSurveyItems() as $item) {
                    $conditions = $this->getItemConditions($item, $conditions);
                }
                break;
        }

        return $conditions;
    }
}
