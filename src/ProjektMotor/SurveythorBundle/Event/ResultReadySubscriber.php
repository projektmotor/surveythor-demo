<?php

namespace PM\SurveythorBundle\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * ResultReadySubscriber
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class ResultReadySubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array('result.ready' => 'onResultReady');
    }

    public function onResultReady(ResultEvent $event)
    {
    }
}
