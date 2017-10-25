<?php

namespace PM\SurveythorBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;
use PM\SurveythorBundle\Entity\SurveyItem;

class SurveyItemRepository
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
        $this->repository = $entityManager->getRepository(SurveyItem::class);
        $this->entityManager = $entityManager;
    }

    /**
     * @param int|array $id
     *
     * @return SurveyItem
     * @throws EntityNotFoundException
     */
    public function findOneById($id)
    {
        $surveyItem = $this->repository->findOneBy(['id' => $id]);

        if (is_null($surveyItem)) {
            throw new EntityNotFoundException(
                sprintf('surveyItem not found for id "%s"', $id)
            );
        }

        return $surveyItem;
    }

    /**
     * @param SurveyItem $surveyItem
     */
    public function detach(SurveyItem $surveyItem)
    {
        $this->entityManager->detach($surveyItem);
    }

    /**
     * @param SurveyItem $surveyItem
     */
    public function remove(SurveyItem $surveyItem)
    {
        $this->entityManager->remove($surveyItem);
        $this->entityManager->flush();
    }

    /**
     * @param SurveyItem $surveyItem
     */
    public function save(SurveyItem $surveyItem)
    {
        $this->entityManager->persist($surveyItem);
        $this->entityManager->flush();
    }
}
