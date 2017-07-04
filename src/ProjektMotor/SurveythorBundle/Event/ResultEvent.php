<?php

namespace PM\SurveythorBundle\Event;

use PM\SurveythorBundle\Entity\Result;
use Symfony\Component\HttpKernel\Event\GetResponseEvent as Event;

/**
 * ResultEvent
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class ResultEvent extends Event
{
    const NAME = 'result.ready';

    protected $result;

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
}
