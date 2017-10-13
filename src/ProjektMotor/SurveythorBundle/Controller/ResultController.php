<?php

namespace PM\SurveythorBundle\Controller;

use AppBundle\Event\ResultReadySubscriber;
use PM\SurveythorBundle\Entity\Result;
use PM\SurveythorBundle\Entity\ResultItem;
use PM\SurveythorBundle\Entity\Survey;
use PM\SurveythorBundle\Entity\SurveyItem;
use PM\SurveythorBundle\Entity\SurveyItems\ItemGroup;
use PM\SurveythorBundle\Entity\SurveyItems\Question;
use PM\SurveythorBundle\Entity\SurveyItems\TextItem;
use PM\SurveythorBundle\Event\ResultEvent;
use PM\SurveythorBundle\Form\ResultItemType;
use PM\SurveythorBundle\Repository\ResultRepository;
use QafooLabs\MVC\FormRequest;
use QafooLabs\MVC\RedirectRoute;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

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
     * @var ResultRepository
     */
    private $resultRepository;

    /**
     * @param EventSubscriberInterface $resultReadySubscriber
     * @param ResultRepository         $resultRepository
     */
    public function __construct(
        EventSubscriberInterface $resultReadySubscriber,
        ResultRepository $resultRepository
    ) {
        $this->resultReadySubscriber = $resultReadySubscriber;
        $this->resultRepository = $resultRepository;
    }

    /**
     * @param Survey $survey
     *
     * @return array
     */
    public function newAction(Survey $survey)
    {
        return [
            'survey' => $survey,
        ];
    }

    /**
     * @param Survey $survey
     *
     * @return array
     */
    public function firstAction(Survey $survey, FormRequest $formRequest)
    {
        $result = new Result();
        $surveyItem = $survey->getSurveyItems()->first();
        $resultItem = $this->prepareResultItem($surveyItem, $result);

        $result->setSurvey($survey);
        $this->resultRepository->save($result);

        $formRequest->handle(ResultItemType::class, $resultItem);

        return [
            'item' => $surveyItem,
            'result' => $result,
            'survey' => $result->getSurvey(),
            'form' => $formRequest->createFormView(),
            'isLast' => $this->isLastItem($surveyItem, $result),
        ];
    }

    /**
     * @param FormRequest $formRequest
     * @param SurveyItem  $surveyItem
     * @param Result      $result
     *
     * @return array|RedirectRoute
     */
    public function nextAction(FormRequest $formRequest, SurveyItem $surveyItem, Result $result)
    {
        $resultItem = $this->prepareResultItem($surveyItem, $result);
        if (!$formRequest->handle(ResultItemType::class, $resultItem)) {

            return [
                'item' => $surveyItem,
                'result' => $result,
                'survey' => $result->getSurvey(),
                'form' => $formRequest->createFormView(),
                'isLast' => $this->isLastItem($surveyItem, $result),
            ];
        }

        $resultItem = $formRequest->getValidData();
        $result->addResultItem($resultItem);
        $this->resultRepository->save($result);

        $nextSurveyItem = $this->getNextItem($surveyItem, $result);

        return new RedirectRoute(
            'result_next', ['surveyItem' => $nextSurveyItem->getId(), 'result' => $result->getId()]
        );
    }

    /**
     * @param FormRequest $formRequest
     * @param SurveyItem  $surveyItem
     * @param Result      $result
     *
     * @return array|JsonResponse
     */
    public function prevAction(FormRequest $formRequest, SurveyItem $surveyItem, Result $result)
    {
        $prevItem = $this->getPrevItem($surveyItem, $result);
        $resultItem = $this->prepareResultItem($prevItem, $result);

        $formRequest->handle(ResultItem::class, $resultItem);

        return [
            'item' => $surveyItem,
            'result' => $result,
            'survey' => $result->getSurvey(),
            'form' => $formRequest->createFormView(),
            'isLast' => $this->isLastItem($surveyItem, $result),
        ];
    }

    /**
     * @param FormRequest $formRequest
     * @param SurveyItem  $surveyItem
     * @param Result      $result
     *
     * @return array|JsonResponse
     */
    public function lastAction(FormRequest $formRequest, SurveyItem $surveyItem, Result $result)
    {
        $resultItem = $this->prepareResultItem($surveyItem, $result);
        if (!$formRequest->handle(ResultItemType::class, $resultItem)) {

            return [
                'item' => $surveyItem,
                'result' => $result,
                'survey' => $result->getSurvey(),
                'form' => $formRequest->createFormView(),
                'isLast' => $this->isLastItem($surveyItem, $result),
            ];
        }

        $resultItem = $formRequest->getValidData();
        $result->addResultItem($resultItem);
        $this->resultRepository->save($result);

        $dispatcher = new EventDispatcher();
        $event = new ResultEvent($result);

        $dispatcher->addSubscriber($this->resultReadySubscriber);
        $dispatcher->dispatch(ResultEvent::NAME, $event);

        return new JsonResponse(
            [
                'status' => 'finished',
                'url' => $event->getUrl(),
            ]
        );
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
     * @return bool|SurveyItem
     */
    private function getPrevItem(SurveyItem $item, Result $result)
    {
        $survey = $item->getSurvey();
        if ($prev = $survey->getPrevItem($item)) {
            if ($this->isItemVisible($prev, $result)) {
                return $prev;
            }

            return $this->getPrevItem($prev, $result);
        }

        return false;
    }

    /**
     * @param SurveyItem $currentItem
     * @param Result     $result
     *
     * @return bool
     */
    private function isLastItem(SurveyItem $currentItem, Result $result)
    {
        $survey = $currentItem->getSurvey();
        $nextItem = $survey->getNextItem($currentItem);

        if ($nextItem) {
            if ($this->isItemVisible($nextItem, $result)) {
                return false;
            }

            return $this->isLastItem($nextItem, $result);
        }

        return true;
    }

    /**
     * @param SurveyItem $surveyItem
     * @param Result     $result
     *
     * @return ResultItem
     * @throws \Exception
     */
    private function prepareResultItem(SurveyItem $surveyItem, Result $result)
    {
        if ($surveyItem instanceof Question) {
            $resultItem = $surveyItem->createResultItem();
        } elseif ($surveyItem instanceof TextItem) {
            $resultItem = $surveyItem->createResultItem();
        } elseif ($surveyItem instanceof ItemGroup) {
            $resultItem = new ResultItem();
            $childItems = $surveyItem->getSurveyItems();
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
            $resultItem->setSurveyItem($surveyItem);
        } else {
            throw new \Exception('surveyItem has to be one of Question, ItemGroup or ResultItem');
        }

        return $resultItem;
    }

    /**
     * @param SurveyItem $item
     * @param Result     $result
     *
     * @return bool|null
     *
     * @todo too complex => lower complexity or unit test
     */
    private function isItemVisible(SurveyItem $item, Result $result)
    {
        $visible = true;
        if (0 != sizeof($item->getConditions())) {
            $resultChoices = array();
            foreach ($result->getResultItems() as $resultItem) {
                if ($answer = $resultItem->getSingleChoiceAnswer()) {
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
}
