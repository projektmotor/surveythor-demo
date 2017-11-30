<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Result;
use AppBundle\Entity\ResultItem;
use AppBundle\Entity\Survey;
use AppBundle\Event\ResultEvent;
use AppBundle\Event\ResultReadySubscriber;
use AppBundle\Form\ResultItemType;
use AppBundle\Repository\ResultRepository;
use QafooLabs\MVC\FormRequest;
use QafooLabs\MVC\RedirectRoute;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Exception\SessionUnavailableException;

/**
 * Class ResultController
 * @package AppBundle\Controller
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
     * @param string $embedFrame
     * @param SessionInterface $session
     * @return array
     */
    public function newAction(Survey $survey, SessionInterface $session, $embedFrame = null): array
    {
        // if embedFrame parameter was omitted try to get
        // embedFrame from session
        if (!$embedFrame) {
            try {
                $embedFrame = $session->get('embedFrame');
            } catch(SessionUnavailableException $e) {
                throw new SessionUnavailableException('Fehler beim Zugriff auf Session. '.$e->getMessage());
            }
        }

        // if embedFrame parameter was omitted and not set in session
        // use github frame; setting github as default parameter value
        // defeats the purpose of loading it from the session
        if (!$embedFrame) {
            $embedFrame = 'github';
        }

        $session->set('embedFrame', $embedFrame);

        return [
            'survey' => $survey,
            'embedFrame' => $embedFrame,
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
