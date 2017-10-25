<?php

namespace PM\SurveythorBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PM\SurveythorBundle\Entity\Condition;
use PM\SurveythorBundle\Entity\SurveyItems\Question;
use PM\SurveythorBundle\Entity\Choice;

class ConditionRepository
{
    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(Condition::class);
    }

    /**
     * @param Question $question
     *
     * @return Condition[]
     */
    public function getConditionsByQuestion(Question $question)
    {
        $qb = $this->repository->createQueryBuilder('c');
        $qb
            ->select('c')
            ->join('c.choices', 'ch')
            ->where('ch.question = :idQuestion')
            ->setParameter('idQuestion', $question->getId());

        return $qb->getQuery()->getResult();
    }

    /**
     * @param Choice $choice
     *
     * @return Condition[]
     */
    public function getConditionsByChoice(Choice $choice)
    {
        $qb = $this->repository->createQueryBuilder('c');
        $qb
            ->select('c')
            ->join('c.choices', 'ch')
            ->where('c.id = :idChoice')
            ->setParameter('idChoice', $choice->getId());

        return $qb->getQuery()->getResult();
    }
}
