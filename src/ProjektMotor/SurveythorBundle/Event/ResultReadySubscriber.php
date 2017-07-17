<?php
namespace PM\SurveythorBundle\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use PM\SurveythorBundle\Event\ResultEvent;
use PM\SurveythorBundle\Repository\ResultRepository;

/**
 * ResultReadySubscriber
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class ResultReadySubscriber implements EventSubscriberInterface
{
    private $resultRepository;

    public function __construct(ResultRepository $resultRepository)
    {
        $this->resultRepository = $resultRepository;
    }

    public static function getSubscribedEvents()
    {
        return array('result.ready' => 'onResultReady');
    }

    public function onResultReady(ResultEvent $event)
    {
        $this->resultRepository->save($event->getResult());
        dump($event->getResult());
        die('moo');
    }
}
