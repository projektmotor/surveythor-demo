<?php
namespace PM\SurveythorBundle\Controller;

use AppBundle\Event\ResultReadySubscriber;
use PM\SurveythorBundle\Entity\Answer;
use PM\SurveythorBundle\Entity\Choice;
use PM\SurveythorBundle\Entity\Result;
use PM\SurveythorBundle\Entity\ResultItem;
use PM\SurveythorBundle\Entity\ResultItems\MultipleChoiceAnswer;
use PM\SurveythorBundle\Entity\ResultItems\SingleChoiceAnswer;
use PM\SurveythorBundle\Entity\ResultItems\TextAnswer;
use PM\SurveythorBundle\Entity\ResultItems\TextItem as ResultTextItem;
use PM\SurveythorBundle\Entity\Survey;
use PM\SurveythorBundle\Entity\SurveyItem;
use PM\SurveythorBundle\Entity\SurveyItems\ItemGroup;
use PM\SurveythorBundle\Entity\SurveyItems\Question;
use PM\SurveythorBundle\Entity\SurveyItems\TextItem;
use PM\SurveythorBundle\Event\ResultEvent;
use PM\SurveythorBundle\Form\ResultItemType;
use PM\SurveythorBundle\Repository\ResultRepository;
use QafooLabs\MVC\FormRequest;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Twig_Environment;

/**
 * Class ResultController
 * @package PM\SurveythorBundle\Controller
 */
class ResultController
{
    /**
     * @var ResultReadySubscriber
     */
    private $resultReadySubscriber;

    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @var ResultRepository
     */
    private $resultRepository;

    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @var Router
     */
    private $router;


    /**
     * @param EventSubscriberInterface $resultReadySubscriber
     * @param FormFactory              $formFactory
     * @param ResultRepository         $resultRepository
     * @param Twig_Environment         $twig
     * @param Router               $router
     */
    public function __construct(
        EventSubscriberInterface $resultReadySubscriber,
        FormFactory $formFactory,
        ResultRepository $resultRepository,
        Twig_Environment $twig,
        Router $router
    ) {
        $this->resultReadySubscriber = $resultReadySubscriber;
        $this->formFactory = $formFactory;
        $this->resultRepository = $resultRepository;
        $this->twig = $twig;
        $this->router = $router;
    }

    /**
     * @param Survey $survey
     *
     * @return array
     */
    public function newAction(Survey $survey)
    {
        return array(
            'survey' => $survey
        );
    }

    public function firstAction(Survey $survey)
    {
        $result = new Result();
        $surveyItem = $survey->getSurveyItems()->first();
        $resultItem = $this->prepareResultItem($surveyItem, $result);
        $nextSurveyItem = $this->getNextItem($surveyItem, $result);

        $result->setSurvey($survey);
        $this->resultRepository->save($result);

        $form = $this->formFactory->create(ResultItemType::class, $resultItem);
        $html = $this->twig->render(
            '@PMSurveythorBundle/Result/next.html.twig',
            array(
                'item' => $surveyItem,
                'result' => $result,
                'survey' => $survey,
                'form' => $form->createView(),
                'isLast' => $this->isLastItem($surveyItem, $result)
            )
        );

        return new JsonResponse(json_encode(array(
            'status' => 'OK',
            'html' => $html
        )));
    }

    /**
     * @param FormRequest $formRequest
     * @param Survey      $survey
     * @param SurveyItem  $surveyItem
     * @param Result      $result
     *
     * @return array|JsonResponse
     */
    public function nextAction(FormRequest $formRequest, Survey $survey, SurveyItem $surveyItem, Result $result)
    {
        $resultItem = $this->prepareResultItem($surveyItem, $result);
        if (!$formRequest->handle(ResultItemType::class, $resultItem)) {
            $html = $this->twig->render(
                '@PMSurveythorBundle/Result/next.html.twig',
                array(
                    'item' => $surveyItem,
                    'result' => $result,
                    'survey' => $survey,
                    'form' => $formRequest->getForm()->createView(),
                    'isLast' => $this->isLastItem($surveyItem, $result)
                )
            );

            return new JsonResponse(json_encode(array(
                'status' => 'OK',
                'html' => $html
            )));
        }

        $resultItem = $formRequest->getValidData();
        $result->addResultItem($resultItem);
        $this->resultRepository->save($result);

        $nextSurveyItem = $this->getNextItem($surveyItem, $result);
        $nextResultItem = $this->prepareResultItem($nextSurveyItem, $result);

        $form = $this->formFactory->create(ResultItemType::class, $nextResultItem);
        $html = $this->twig->render(
            '@PMSurveythorBundle/Result/next.html.twig',
            array(
                'item' => $nextSurveyItem,
                'result' => $result,
                'survey' => $survey,
                'form' => $form->createView(),
                'isLast' => $this->isLastItem($nextSurveyItem, $result)
            )
        );

        return new JsonResponse(json_encode(array(
            'status' => 'OK',
            'html' => $html
        )));
    }

    /**
     * @param FormRequest $formRequest
     * @param Survey      $survey
     * @param SurveyItem  $surveyItem
     * @param Result      $result
     *
     * @return array|JsonResponse
     */
    public function lastAction(FormRequest $formRequest, Survey $survey, SurveyItem $surveyItem, Result $result)
    {
        $resultItem = $this->prepareResultItem($surveyItem, $result);
        if (!$formRequest->handle(ResultItemType::class, $resultItem)) {
            $html = $this->twig->render(
                '@PMSurveythorBundle/Result/next.html.twig',
                array(
                    'item' => $surveyItem,
                    'result' => $result,
                    'survey' => $survey,
                    'form' => $formRequest->getForm()->createView(),
                    'isLast' => false
                )
            );

            return new JsonResponse(json_encode(array(
                'status' => 'OK',
                'html' => $html
            )));
        }

        $resultItem = $formRequest->getValidData();
        $result->addResultItem($resultItem);
        $this->resultRepository->save($result);

        $dispatcher = new EventDispatcher();
        $event = new ResultEvent($result);

        $dispatcher->addSubscriber($this->resultReadySubscriber);
        $dispatcher->dispatch(ResultEvent::NAME, $event);

        $jsonResponse = new JsonResponse(
            json_encode(
                array(
                    'status' => 'finished',
                    'url' => $event->getUrl()
                )
            ),
            200,
            [
                'Access-Control-Allow-Origin' => 'http://surveythor-demo',
            ]
        );

        return $jsonResponse;
    }

    /**
     * @param SurveyItem $item
     * @param Result     $result
     *
     * @return bool|SurveyItem
     */
    private function getNextItem(SurveyItem $item, Result $result)
    {
        $survey = $item->getSurvey();
        if ($nextItem = $survey->getNextItem($item)) {
            if ($this->isItemVisible($nextItem, $result)) {
                return $nextItem;
            }

            return $this->getNextItem($nextItem, $result);
        }

        return false;
    }

    /**
     * @param SurveyItem $item
     * @param Result     $result
     *
     * @return bool
     */
    private function isLastItem(SurveyItem $item, Result $result)
    {
        $survey = $item->getSurvey();
        if ($nextItem = $survey->getNextItem($item)) {
            if ($this->isItemVisible($nextItem, $result)) {
                return false;
            }
            return $this->isLastItem($nextItem, $result);
        }
        return true;
    }

    /**
     * @param SurveyItem $item
     * @param Result     $result
     *
     * @return bool|null
     *
     */
    private function isItemVisible(SurveyItem $item, Result $result)
    {
        $visible = true;
        if (0 != sizeof($item->getConditions())) {
            $resultChoices = array();
            foreach ($result->getResultItems() as $resultItem) {
                if ($answer = $resultItem->getSingleChoiceAnswer()) {
                    //dump($answer); die();
                    $resultChoices[] = $answer->getChoice()->getId();
                }
                if ($answer = $resultItem->getMultipleChoiceAnswer()) {
                    foreach ($answer->getChoices() as $choice) {
                        $resultChoices[] = $choice->getId();
                    }
                }
            }

            foreach ($item->getConditions() as $condition) {
                foreach ($condition->getChoices() as $choice) {
                    if (false === $condition->getIsNegative()) {
                        $visible = !in_array($choice->getId(), $resultChoices) ? false : true;
                        if (true === $visible) {
                            continue 2;
                        }

                    } else {
                        $visible = in_array($choice->getId(), $resultChoices) ? false : $visible;
                    }
                }
                if (false === $visible) {
                    return false;
                }
            }
        }

        return $visible;
    }

    /**
     * @param SurveyItem $item
     * @param Result     $result
     *
     * @return ResultItem
     */
    private function prepareResultItem(SurveyItem $item, Result $result)
    {
        if ($item instanceof Question) {
            $resultItem = $this->prepareAnswer($item);
        }

        if ($item instanceof TextItem) {
            $resultItem = new ResultItem();

            $textItem = new ResultTextItem();
            $textItem->setText($item->getText());
            $resultItem->setTextItem($textItem);
        }

        if ($item instanceof ItemGroup) {
            $resultItem = new ResultItem();
            $childItems = $item->getSurveyItems();
            $childItem = $childItems->current();
            if ($this->isItemVisible($childItem, $result)) {
                $resultItem = new ResultItem();
                $resultItem->addChildItem($this->prepareResultItem($childItem, $result));
            }
            while ($childItem = $childItems->next()) {
                if ($this->isItemVisible($childItem, $result)) {
                    $resultItem->addChildItem(
                        $this->prepareResultItem($childItem, $result)
                    );
                }
            }
        }

        $resultItem->setSurveyItem($item);

        return $resultItem;
    }

    /**
     * @param SurveyItem $item
     *
     * @return ResultItem
     */
    private function prepareAnswer(SurveyItem $item)
    {
        $resultItem = new ResultItem();
        $answer = Answer::createByQuestionType($item);
        $answer->setQuestion($item);

        if ($answer instanceof MultipleChoiceAnswer) {
            $resultItem->setMultipleChoiceAnswer($answer);
        }
        if ($answer instanceof SingleChoiceAnswer) {
            $resultItem->setSingleChoiceAnswer($answer);
        }
        if ($answer instanceof TextAnswer) {
            $resultItem->setTextAnswer($answer);
        }

        return $resultItem;
    }
}
