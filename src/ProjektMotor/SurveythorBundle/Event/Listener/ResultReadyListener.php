<?php
namespace PM\SurveythorBundle\Event\Listener;

use Symfony\Component\EventDispatcher\Event;
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

    public function onResultReady(Event $event)
    {
        $this->resultRepository->save($event->getResult());
        dump($event->getResult());
        die();
    }
}
