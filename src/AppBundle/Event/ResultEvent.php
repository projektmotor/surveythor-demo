<?php

namespace AppBundle\Event;

use AppBundle\Entity\Result;
use Symfony\Component\EventDispatcher\Event;

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
     * @var string
     */
    private $url;

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
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }
}
