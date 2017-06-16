<?php
namespace PM\SurveythorBundle\Event;

use PM\SurveythorBundle\Entity\Result;
use Symfony\Component\EventDispatcher\Event;

/**
 * ResultEvent
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class ResultEvent extends Event
{
    const NAME = 'result.ready';

    protected $result;

    public function __construct(Result $result)
    {
        $this->result = $result;
    }

    /**
     * Get result.
     *
     * @return result.
     */
    public function getResult()
    {
        return $this->result;
    }
}
