<?php

namespace PM\SurveythorBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use PM\SurveythorBundle\Entity\Choice;
use PM\SurveythorBundle\Entity\Condition;
use PM\SurveythorBundle\Entity\SurveyItems\Question;

class ConditionRepository
{
    /**
     * @var Registry
     */
    private $registry;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @param Question $question
     *
     * @return Condition[]
     */
    public function getConditionsByQuestion(Question $question)
    {
        $qb = $this->getManager()->createQueryBuilder()
            ->from(Condition::class, 'c')
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
        $qb = $this->getManager()->createQueryBuilder()
            ->from(Condition::class, 'c')
            ->select('c')
            ->join('c.choices', 'ch')
            ->where('c.id = :idChoice')
            ->setParameter('idChoice', $choice->getId());

        return $qb->getQuery()->getResult();
    }

    /**
     * @return EntityManager
     */
    private function getManager()
    {
        /** @var EntityManager $manager */
        $manager = $this->registry->getManagerForClass(Condition::class);

        return $manager;
    }
}
