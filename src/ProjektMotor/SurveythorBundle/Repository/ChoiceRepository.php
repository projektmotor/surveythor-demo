<?php

namespace PM\SurveythorBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PM\SurveythorBundle\Entity\Choice;

class ChoiceRepository
{
    /**
     * @var EntityRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(Choice::class);
        $this->entityManager = $entityManager;
    }

    /**
     * @param Choice $choice
     */
    public function remove(Choice $choice)
    {
        $this->entityManager->remove($choice);
        $this->entityManager->flush();
    }
}
