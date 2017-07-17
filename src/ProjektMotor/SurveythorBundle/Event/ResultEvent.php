<?php

namespace PM\SurveythorBundle\Event;

use PM\SurveythorBundle\Entity\Result;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Response;

/**
 * ResultEvent
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class ResultEvent extends Event
{
    const NAME = 'result.ready';

    /**
     * @var Result
     */
    private $result;

    /**
     * @var Response
     */
    private $response;

    /**
     * @param Result $result
     */
    public function __construct(Result $result)
    {
        $this->result = $result;
    }

    /**
     * @return Result
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param Response $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }
}
