<?php

namespace PM\SurveythorBundle\Controller;

use AppBundle\Event\ResultReadySubscriber;
use PM\SurveythorBundle\Entity\Result;
use PM\SurveythorBundle\Entity\ResultItem;
use PM\SurveythorBundle\Entity\Survey;
use PM\SurveythorBundle\Event\ResultEvent;
use PM\SurveythorBundle\Form\ResultItemType;
use PM\SurveythorBundle\Repository\ResultRepository;
use QafooLabs\MVC\FormRequest;
use QafooLabs\MVC\RedirectRoute;
use Symfony\Component\EventDispatcher\EventDispatcher;
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
     * @param ResultReadySubscriber $resultReadySubscriber
     * @param ResultRepository      $resultRepository
     */
    public function __construct(
        ResultReadySubscriber $resultReadySubscriber,
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
     * @param Survey      $survey
     * @param FormRequest $formRequest
     *
     * @return array
     */
    public function firstAction(Survey $survey, FormRequest $formRequest)
    {
        $result = Result::createBySurvey($survey);
        $currentResultItem = $result->getCurrentResultItem();

        $this->resultRepository->save($result);

        $formRequest->handle(ResultItemType::class, $currentResultItem);

        return [
            'result' => $result,
            'resultItem' => $currentResultItem,
            'form' => $formRequest->createFormView(),
            'hasPrev' => !$result->getResultItems()->first()->isCurrent(),
            'isLast' => $result->getResultItems()->last()->isCurrent(),
        ];
    }

    /**
     * @param FormRequest $formRequest
     * @param ResultItem  $resultItem
     * @param Result      $result
     *
     * @return array|RedirectRoute
     */
    public function nextAction(FormRequest $formRequest, ResultItem $resultItem, Result $result)
    {
        $currentResultItem = $result->getCurrentResultItem();

        if (!$formRequest->handle(ResultItemType::class, $currentResultItem)) {
            return [
                'resultItem' => $resultItem,
                'result' => $result,
                'survey' => $result->getSurvey(),
                'form' => $formRequest->createFormView(),
                'hasPrev' => !$result->getResultItems()->first()->isCurrent(),
                'isLast' => $result->getResultItems()->last()->isCurrent(),
            ];
        }

        $resultItem = $formRequest->getValidData();
        $result->markNextResultItemAsCurrent();
        $this->resultRepository->save($result);

        return new RedirectRoute(
            'result_next', ['resultItem' => $result->getCurrentResultItem()->getId(), 'result' => $result->getId()]
        );
    }

    /**
     * @param Result $result
     *
     * @return RedirectRoute
     */
    public function prevAction(Result $result)
    {
        $result->markPreviousResultItemAsCurrent();
        $currentResultItem = $result->getCurrentResultItem();

        $this->resultRepository->save($result);

        return new RedirectRoute(
            'result_next', ['resultItem' => $currentResultItem->getId(), 'result' => $result->getId()]
        );
    }

    /**
     * @param FormRequest $formRequest
     * @param ResultItem  $resultItem
     * @param Result      $result
     *
     * @return array|JsonResponse
     */
    public function lastAction(FormRequest $formRequest, ResultItem $resultItem, Result $result)
    {
        $resultItem = $result->getResultItems()->last();
        if (!$formRequest->handle(ResultItemType::class, $resultItem)) {

            return [
                'resultItem' => $resultItem,
                'result' => $result,
                'form' => $formRequest->createFormView(),
                'hasPrev' => !$result->getResultItems()->first()->isCurrent(),
                'isLast' => $result->getResultItems()->last()->isCurrent(),
            ];
        }

        $resultItem = $formRequest->getValidData();
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
}
