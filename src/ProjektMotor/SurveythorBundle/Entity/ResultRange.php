<?php

namespace PM\SurveythorBundle\Entity;

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
     * @var \PM\SurveythorBundle\Entity\Survey
     */
    private $survey;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set meaning
     *
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
     * Get meaning
     *
     * @return string
     */
    public function getMeaning()
    {
        return $this->meaning;
    }

    /**
     * Set start
     *
     * @param integer $start
     *
     * @return ResultRange
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return int
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set stop
     *
     * @param integer $stop
     *
     * @return ResultRange
     */
    public function setStop($stop)
    {
        $this->stop = $stop;

        return $this;
    }

    /**
     * Get stop
     *
     * @return int
     */
    public function getStop()
    {
        return $this->stop;
    }

    /**
     * Set event
     *
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
     * Get event
     *
     * @return string
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Get survey.
     *
     * @return survey.
     */
    public function getSurvey()
    {
        return $this->survey;
    }

    /**
     * Set survey.
     *
     * @param survey the value to set.
     */
    public function setSurvey($survey)
    {
        $this->survey = $survey;
    }
}
