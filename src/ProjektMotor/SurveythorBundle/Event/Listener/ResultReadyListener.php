<?php
namespace PM\SurveythorBundle\Event\Listener;

use PM\SurveythorBundle\Event\ResultEvent;
use PM\SurveythorBundle\Repository\ResultRepository;

/**
 * ResultReadyListener
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class ResultReadyListener
{
    private $resultRepository;

    public function __construct(ResultRepository $resultRepository)
    {
        $this->resultRepository = $resultRepository;
    }

    public function onResultReady(ResultEvent $event)
    {
        $this->resultRepository->save($event->getResult());
        dump($event->getResult());
        die();
    }
}
