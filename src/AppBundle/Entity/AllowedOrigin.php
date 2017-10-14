<?php

namespace AppBundle\Entity;

use Ramsey\Uuid\Uuid;

class AllowedOrigin
{
    /** @var Uuid */
    private $id;
    /** @var string */
    private $originName;
    /** @var string */
    private $title;
    /** @var boolean */
    private $isActive = false;
    /** @var \DateTime */
    private $createdAt;
    /** @var \DateTime */
    private $updatedAt;
    /** @var string */
    private $createdBy;
    /** @var string */
    private $updatedBy;

    /**
     * @return Uuid
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $originName
     *
     * @return AllowedOrigin
     */
    public function setOriginName($originName)
    {
        $this->originName = $originName;

        return $this;
    }

    /**
     * @param string $title
     *
     * @return AllowedOrigin
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param bool $isActive
     *
     * @return AllowedOrigin
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return AllowedOrigin
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @param \DateTime $updatedAt
     *
     * @return AllowedOrigin
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @param string $createdBy
     *
     * @return AllowedOrigin
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @param string $updatedBy
     *
     * @return AllowedOrigin
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * @return string
     */
    public function getOriginName()
    {
        return $this->originName;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return string
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @return string
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }
}
