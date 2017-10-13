<?php
namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use PM\SurveythorBundle\Event\ResultEvent;
use PM\SurveythorBundle\Repository\ResultRepository;

/**
 * ResultReadySubscriber
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class ResultReadySubscriber implements EventSubscriberInterface
{
    private $router;

    /**
     * @param Router $router
     */
    public function __construct(
        Router $router
    ) {
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return array('result.ready' => 'onResultReady');
    }

    /**
     * @param ResultEvent $event
     */
    public function onResultReady(ResultEvent $event)
    {
        $url = $this->router->generate(
            'result_evaluation',
            ['id' => $event->getResult()->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $event->setUrl($url);
    }
}
