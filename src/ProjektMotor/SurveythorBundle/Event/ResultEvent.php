<?php

namespace PM\SurveythorBundle\Event;

use PM\SurveythorBundle\Entity\Result;
//use Symfony\Component\HttpKernel\Event\GetResponseEvent as Event;
use Symfony\Component\EventDispatcher\Event;

/**
 * ResultEvent
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class ResultEvent extends Event
{
    const NAME = 'result.ready';

    protected $result;
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
     * Get response.
     *
     * @return response.
     */
    public function getResponse()
    {
        return $this->response;
    }
    
    /**
     * Set response.
     *
     * @param response the value to set.
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }
}
