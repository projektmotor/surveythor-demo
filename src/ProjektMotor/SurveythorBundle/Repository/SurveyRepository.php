<?php

namespace PM\SurveythorBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;
use PM\SurveythorBundle\Entity\Survey;

class SurveyRepository
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
        $this->repository = $entityManager->getRepository(Survey::class);
        $this->entityManager = $entityManager;
    }

    /**
     * @param mixed $id
     *
     * @return Survey
     * @throws EntityNotFoundException
     */
    public function find($id)
    {
        $survey = $this->repository->find($id);
        if (is_null($survey)) {
            throw new EntityNotFoundException(sprintf('no survey found for id "%s"', json_encode($id)));
        }

        return $survey;
    }

    /**
     * @return Survey[]
     */
    public function findAll()
    {
        return $this->repository->findAll();
    }

    /**
     * @param Survey $survey
     */
    public function save(Survey $survey)
    {
        $this->entityManager->persist($survey);
        $this->entityManager->flush();
    }
}
