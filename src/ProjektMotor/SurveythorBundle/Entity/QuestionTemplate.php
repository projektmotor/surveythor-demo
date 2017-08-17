<?php

namespace PM\SurveythorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * QuestionTemplate
 */
class QuestionTemplate
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
     * @var string
     */
    private $result;

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
     * @return QuestionTemplate
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
     * Set result
     *
     * @param string $result
     *
     * @return QuestionTemplate
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Get result
     *
     * @return string
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Get formType.
     *
     * @return formType.
     */
    public function getFormType()
    {
        return $this->formType;
    }

    /**
     * Set formType.
     *
     * @param formType the value to set.
     */
    public function setFormType($formType)
    {
        $this->formType = $formType;
    }
}
