<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Condition;
use AppBundle\Entity\Factory\SurveyItemFactory;
use AppBundle\Entity\Survey;
use AppBundle\Entity\SurveyItem;
use AppBundle\Entity\SurveyItems\ItemGroup;
use AppBundle\Entity\SurveyItems\Question;
use AppBundle\Form\SurveyItemType;
use AppBundle\Repository\ConditionRepository;
use AppBundle\Repository\SurveyItemRepository;
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
     * @param SurveyItem  $surveyItem
     *
     * @return JsonResponse
     */
    public function updateAction(FormRequest $formRequest, SurveyItem $surveyItem)
    {
        if (!$formRequest->handle(
            SurveyItemType::class,
            $surveyItem,
            array('action' => $this->router->generate(
                'survey_item_update',
                array('surveyItem' => $surveyItem->getId())
            ))
        )) {
            return new JsonResponse(
                [
                    'status' => 'NOT VALID',
                    'open' => [$surveyItem->getId()],
                ]
            );
        }

        $surveyItem = $formRequest->getValidData();
        $this->surveyItemRepository->save($surveyItem);

        return new JsonResponse(
            [
                'status' => 'OK',
                'open' => [$surveyItem->getId()],
            ]
        );
    }

    /**
     * @param Survey $survey
     * @param string $type
     *
     * @return JsonResponse
     */
    public function newAction(Survey $survey, $type)
    {
        $surveyItem = SurveyItemFactory::createByType($type);
        $surveyItem->setSurvey($survey);
        $this->surveyItemRepository->save($surveyItem);

        $form = $this->formFactory->create(
            SurveyItemType::class,
            $surveyItem,
            [
                'action' => $this->router->generate(
                    'survey_item_update',
                    ['surveyItem' => $surveyItem->getId()]
                ),
            ]
        );
        $html = $this->twig->render(
            '@AppBundle/SurveyItem/new.html.twig',
            ['form' => $form->createView()]
        );

        return new JsonResponse(
            [
                'html' => $html,
                'open' => [$surveyItem->getId()],
                'status' => 'OK',
            ]
        );
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
        /** @var ItemGroup $parentItemGroup */
        $parentItemGroup = $this->surveyItemRepository->findOneById($request->query->get('parent'));
        $sortOrder = $request->query->get('sortorder');
        $item = SurveyItemFactory::createByType($type);

        foreach ($parentItemGroup->getSurveyItems() as $surveyItem) {
            if ($surveyItem->getSortOrder() >= $sortOrder) {
                $surveyItem->setSortOrder($surveyItem->getSortOrder() + 1);
                $this->surveyItemRepository->save($surveyItem);
            }
        }

        $parentItemGroup->addSurveyItem($item);
        $item->setSortOrder($sortOrder);
        $this->surveyItemRepository->save($parentItemGroup);
        $this->surveyItemRepository->detach($parentItemGroup);
        $parentItemGroup = $this->surveyItemRepository->findOneById($request->query->get('parent'));

        $form = $this->formFactory->create(
            SurveyItemType::class,
            $parentItemGroup->getRoot(),
            [
                'action' => $this->router->generate(
                    'survey_item_update',
                    ['surveyItem' => $parentItemGroup->getRoot()->getId()]
                ),
            ]
        );

        $html = $this->twig->render(
            '@AppBundle/SurveyItem/itemGroupAddItem.html.twig',
            ['form' => $form->createView()]
        );

        return new JsonResponse(
            [
                'html' => $html,
                'open' => array_merge([$item->getId()], $parentItemGroup->getGroupIdsFromTop()),
                'root' => $parentItemGroup->getRoot()->getId(),
                'status' => 'OK',
            ]
        );
    }

    /**
     * @param SurveyItem $surveyItem
     *
     * @return array
     */
    public function formAction(SurveyItem $surveyItem)
    {
        $form = $this->formFactory->create(
            SurveyItemType::class,
            $surveyItem,
            [
                'action' => $this->router->generate(
                    'survey_item_update',
                    ['surveyItem' => $surveyItem->getId()]
                ),
            ]
        );

        return ['form' => $form->createView()];
    }

    /**
     * @param Request    $request
     * @param SurveyItem $surveyItem
     *
     * @return JsonResponse
     */
    public function setSortOrderAction(Request $request, SurveyItem $surveyItem)
    {
        $sortOrder = $request->query->get('sortorder');
        $surveyItem->setSortOrder($sortOrder);
        $this->surveyItemRepository->save($surveyItem);

        return new JsonResponse(['status' => 'OK']);
    }

    /**
     * @param SurveyItem $surveyItem
     *
     * @return JsonResponse
     */
    public function removeAction(SurveyItem $surveyItem)
    {
        $conditions = $this->getItemConditions($surveyItem);
        if (empty(($conditions))) {
            $id = $surveyItem->getId();
            $this->surveyItemRepository->remove($surveyItem);

            return new JsonResponse(
                [
                    'status' => 'OK',
                    'item' => $id,
                ]
            );
        } else {
            return new JsonResponse(
                [
                    'status' => 'FAIL',
                    'reason' => 'Diese Frage kann nicht gelöscht werden, sie wird von mind. einer Bedingung verwendet.',
                ]
            );
        }
    }

    /**
     * @param SurveyItem $surveyItem
     * @param array      $conditions
     *
     * @return Condition[]
     */
    private function getItemConditions(SurveyItem $surveyItem, $conditions = []): array
    {
        $conditions = $conditions === null ? array() : $conditions;
        switch (true) {
            case ($surveyItem instanceof Question):
                $conditions = $this->conditionRepository->getConditionsByQuestion($surveyItem);
                break;
            case ($surveyItem instanceof ItemGroup):
                foreach ($surveyItem->getSurveyItems() as $surveyItem) {
                    $conditions = $this->getItemConditions($surveyItem, $conditions);
                }
                break;
        }

        return $conditions;
    }
}
