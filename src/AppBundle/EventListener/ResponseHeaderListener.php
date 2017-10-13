<?php

namespace AppBundle\EventListener;

use AppBundle\Repository\AllowedOriginRepository;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class ResponseHeaderListener
{
    /** @var array */
    private static $frontendRoutes = ['result_next', 'result_first', 'result_last'];
    /** @var AllowedOriginRepository */
    private $allowedOriginRepository;

    /**
     * ResponseHeaderListener constructor.
     *
     * @param AllowedOriginRepository $allowedOriginRepository
     */
    public function __construct(AllowedOriginRepository $allowedOriginRepository)
    {
        $this->allowedOriginRepository = $allowedOriginRepository;
    }

    /**
     * Set Headers after rendering the response
     *
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $request = $event->getRequest();
        $response = $event->getResponse();

        // security header
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
            return;
        }

        if (!in_array($request->get('_route'), self::$frontendRoutes)) {
            return;
        }

        $originName = $request->headers->get('origin');
        try {
            $allowedOrigin = $this->allowedOriginRepository->findOneActiveByOriginName($originName);
        } catch (EntityNotFoundException $e) {
            return;
        }

        $response->headers->set('Access-Control-Allow-Origin', $allowedOrigin->getOriginName());
    }
}
