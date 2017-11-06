<?php
declare(strict_types=1);

namespace AppBundle\Entity;

use Ramsey\Uuid\Uuid;

class BunnyUser
{
    /**
     * @var Uuid
     */
    private $id;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $telephoneNumber;

    /**
     * @var string
     */
    private $companyTitle;

    /**
     * @var string
     */
    private $street;

    /**
     * @var string
     */
    private $streetNumber;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $zipCode;

    /**
     * @var boolean
     */
    private $isDataProcessingConfirmed;

    /**
     * @var Result
     */
    private $result;

    public static function createByResult(Result $result): BunnyUser
    {
        $bunnyUser = new self();
        $bunnyUser->setResult($result);

        return $bunnyUser;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setTelephoneNumber(?string $telephoneNumber): void
    {
        $this->telephoneNumber = $telephoneNumber;
    }

    public function getTelephoneNumber(): ?string
    {
        return $this->telephoneNumber;
    }

    public function setCompanyTitle(?string $companyTitle): void
    {
        $this->companyTitle = $companyTitle;
    }

    public function getCompanyTitle(): ?string
    {
        return $this->companyTitle;
    }

    public function setStreet(?string $street): void
    {
        $this->street = $street;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreetNumber(?string $streetNumber): void
    {
        $this->streetNumber = $streetNumber;
    }

    public function getStreetNumber(): ?string
    {
        return $this->streetNumber;
    }

    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setZipCode(?string $zipCode): void
    {
        $this->zipCode = $zipCode;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setIsDataProcessingConfirmed(bool $isDataProcessingConfirmed): void
    {
        $this->isDataProcessingConfirmed = $isDataProcessingConfirmed;
    }

    public function getIsDataProcessingConfirmed(): bool
    {
        return (bool)$this->isDataProcessingConfirmed;
    }

    public function setResult(Result $result): void
    {
        $result->setBunnyUser($this);
        $this->result = $result;
    }

    public function getResult(): Result
    {
        return $this->result;
    }
}

