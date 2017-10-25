<?php

namespace PM\SurveythorBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PM\SurveythorBundle\Entity\Result;

class ResultRepository
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
        $this->repository = $entityManager->getRepository(Result::class);
        $this->entityManager = $entityManager;
    }

    /**
     * @param Result $result
     */
    public function save(Result $result)
    {
        $this->entityManager->persist($result);
        $this->entityManager->flush();
    }
}
