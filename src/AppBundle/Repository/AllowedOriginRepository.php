<?php

namespace AppBundle\Repository;

use AppBundle\Entity\AllowedOrigin;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;

/**
 * AllowedOriginRepository
 */
class AllowedOriginRepository
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
        $this->repository = $entityManager->getRepository(AllowedOrigin::class);
        $this->entityManager = $entityManager;
    }

    /**
     * @return AllowedOrigin[]
     */
    public function findAll()
    {
        return $this->repository->findAll();
    }

    /**
     * @param $originName
     *
     * @return AllowedOrigin
     * @throws EntityNotFoundException
     */
    public function findOneActiveByOriginName($originName)
    {
        $allowedOrigin = $this->repository->findOneBy(['originName' => $originName, 'isActive' => true]);

        if (is_null($allowedOrigin)) {
            throw new EntityNotFoundException(
                sprintf('active allowedOrigin not found for originName "%s"', $originName)
            );
        }

        return $allowedOrigin;
    }

    /**
     * @param AllowedOrigin $allowedOrigin
     */
    public function persist(AllowedOrigin $allowedOrigin)
    {
        $this->entityManager->persist($allowedOrigin);
    }

    /**
     * @param AllowedOrigin $allowedOrigin
     */
    public function save(AllowedOrigin $allowedOrigin)
    {
        $this->entityManager->persist($allowedOrigin);
        $this->entityManager->flush();
    }

    /**
     * @param AllowedOrigin $allowedOrigin
     */
    public function remove(AllowedOrigin $allowedOrigin)
    {
        $this->entityManager->remove($allowedOrigin);
        $this->entityManager->flush();
    }
}
