<?php

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * ResultReadySubscriber
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
abstract class ResultReadySubscriber implements EventSubscriberInterface, ResultReadySubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return ['result.ready' => 'onResultReady'];
    }
}
