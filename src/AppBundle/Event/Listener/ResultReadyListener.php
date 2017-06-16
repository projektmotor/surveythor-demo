<?php
namespace AppBundle\Event\Listener;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use QafooLabs\MVC\RedirectRoute;
use PM\SurveythorBundle\Repository\ResultRepository;

/**
 * ResultReadyListener
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class ResultReadyListener
{
    private $resultRepository;

    private $router;

    public function __construct(
        ResultRepository $resultRepository,
        Router $router
    ) {
        $this->resultRepository = $resultRepository;
        $this->router = $router;
    }

    public function onResultReady(Event $event)
    {
        $this->resultRepository->save($event->getResult());

        $url = $this->router->generate('result_evaluation', ['id' => $event->getResult()->getId()]);
        $event->setResponse(new RedirectResponse($url));
    }
}
