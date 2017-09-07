<?php

namespace PM\SurveythorBundle\Entity;

/**
 * ResultItemTemplate
 */
class ResultItemTemplate
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $formType;


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
     * Set name
     *
     * @param string $name
     *
     * @return ResultItemTemplate
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set formType
     *
     * @param string $formType
     *
     * @return ResultItemTemplate
     */
    public function setformType($formType)
    {
        $this->formType = $formType;

        return $this;
    }

    /**
     * Get formType
     *
     * @return string
     */
    public function getformType()
    {
        return $this->formType;
    }
}

