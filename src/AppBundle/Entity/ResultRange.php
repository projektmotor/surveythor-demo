<?php

namespace AppBundle\Entity;

/**
 * ResultRange
 */
class ResultRange
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $meaning;

    /**
     * @var int
     */
    private $start;

    /**
     * @var int
     */
    private $stop;

    /**
     * @var string
     */
    private $event;

    /**
     * @var Survey
     */
    private $survey;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $meaning
     *
     * @return ResultRange
     */
    public function setMeaning($meaning)
    {
        $this->meaning = $meaning;

        return $this;
    }

    /**
     * @return string
     */
    public function getMeaning()
    {
        return $this->meaning;
    }

    /**
     * @param int $start
     *
     * @return ResultRange
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * @return int
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param int $stop
     *
     * @return ResultRange
     */
    public function setStop($stop)
    {
        $this->stop = $stop;

        return $this;
    }

    /**
     * @return int
     */
    public function getStop()
    {
        return $this->stop;
    }

    /**
     * @param string $event
     *
     * @return ResultRange
     */
    public function setEvent($event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * @return string
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @return Survey
     */
    public function getSurvey()
    {
        return $this->survey;
    }

    /**
     * @param Survey $survey
     */
    public function setSurvey($survey)
    {
        $this->survey = $survey;
    }
}
