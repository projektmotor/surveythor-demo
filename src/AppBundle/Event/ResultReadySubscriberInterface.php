<?php

namespace AppBundle\Event;

interface ResultReadySubscriberInterface
{
    /**
     * Needs to set url at event object.
     *
     * For instance:
     *  $url = $this->router->generate(...);
     *  $event->setUrl($url);
     *
     * @param ResultEvent $event
     */
    public function onResultReady(ResultEvent $event);
}
