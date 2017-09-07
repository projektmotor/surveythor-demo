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
    private $resultRepository;
    private $router;

    /**
     * ResultReadyListener constructor.
     *
     * @param ResultRepository $resultRepository
     * @param Router $router
     */
    public function __construct(
        ResultRepository $resultRepository,
        Router $router
    ) {
        $this->resultRepository = $resultRepository;
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
        $this->resultRepository->save($event->getResult());

        $url = $this->router->generate(
            'result_evaluation',
            ['id' => $event->getResult()->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $event->setUrl($url);
    }
}
