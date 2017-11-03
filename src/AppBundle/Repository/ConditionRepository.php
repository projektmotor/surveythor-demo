<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Choice;
use AppBundle\Entity\Condition;
use AppBundle\Entity\SurveyItems\Question;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;

class ConditionRepository
{
    /**
     * @var ManagerRegistry
     */
    private $registry;

    public function __construct(ManagerRegistry $registry)
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
