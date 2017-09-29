<?php
namespace PM\SurveythorBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\FormFactory;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use QafooLabs\MVC\FormRequest;
use PM\SurveythorBundle\Entity\Survey;
use PM\SurveythorBundle\Entity\SurveyItems\Question;
use PM\SurveythorBundle\Entity\SurveyItems\TextItem;
use PM\SurveythorBundle\Entity\SurveyItems\ItemGroup;
use PM\SurveythorBundle\Entity\SurveyItem;
use PM\SurveythorBundle\Form\SurveyItemType;
use PM\SurveythorBundle\Repository\SurveyItemRepository;
use PM\SurveythorBundle\Entity\Factory\SurveyItemFactory;
use Twig_Environment;

/**
 * SurveyItemController
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class SurveyItemController
{
    /**
     * @var SurveyItemRepository
     */
    private $surveyItemRespository;

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


    public function __construct(
        SurveyItemRepository $surveyItemRespository,
        FormFactory $formFactory,
        Twig_Environment $twig,
        Router $router
    ) {
        $this->surveyItemRepository = $surveyItemRespository;
        $this->formFactory = $formFactory;
        $this->twig = $twig;
        $this->router = $router;
    }

    public function updateAction(FormRequest $formRequest, SurveyItem $item)
    {
        if (!$formRequest->handle(SurveyItemType::class, $item)) {
            return array(
                'form' => $formRequest->createFormView(),
                'parent' => $item->isParent()
            );
        }

        $surveyItem = $formRequest->getValidData();
        $this->surveyItemRepository->save($surveyItem);

        return array(
            'form' => $formRequest->createFormView(),
            'parent' => $item->isParent()
        );
    }

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
                'form'  => $form->createView(),
                'parent' => $item->isParent()
            )
        );

        return new JsonResponse(json_encode(array(
            'html' => $html,
            'open' => array($item->getId()),
            'status' => 'OK'
        )));
    }

    public function itemGroupAddItemAction(Request $request, Survey $survey, $type)
    {
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
        if ($request->query->get('parent') == $request->query->get('root')) {
            $rootItem = $parentItem;
        } else {
            $rootItem = $this->surveyItemRepository->findOneById($request->query->get('root'));
        }

        $form = $this->formFactory->create(
            SurveyItemType::class,
            $rootItem,
            array('action' => $this->router->generate(
                'surveyitem_update',
                array('item' => $rootItem->getId())
            ))
        );

        $html = $this->twig->render(
            '@PMSurveythorBundle/SurveyItem/itemGroupAddItem.html.twig',
            array('form'  => $form->createView())
        );

        return new JsonResponse(json_encode(array(
            'html' => $html,
            'open' => array($item->getId(), $parentItem->getId()),
            'status' => 'OK'
        )));
    }

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

    public function setSortOrderAction(SurveyItem $item, $sortOrder)
    {
        $item->setSortOrder($sortOrder);
        $this->surveyItemRepository->save($item);

        return new JsonResponse(json_encode(['status' => 'OK']));
    }
}
