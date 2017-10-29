<?php

namespace PM\SurveythorBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use PM\SurveythorBundle\Entity\Result;
use PM\SurveythorBundle\Entity\Survey;

class ResultRepository
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
     * @param Survey $survey
     *
     * @return null|Result
     */
    public function findOneBySurvey(Survey $survey)
    {
        return $this->getManager()->find(Result::class, $survey->getId());
    }

    /**
     * @param Result $result
     */
    public function save(Result $result)
    {
        $this->getManager()->persist($result);
        $this->getManager()->flush();
    }

    /**
     * @return EntityManager
     */
    private function getManager()
    {
        /** @var EntityManager $manager */
        $manager = $this->registry->getManagerForClass(Result::class);

        return $manager;
    }
}
