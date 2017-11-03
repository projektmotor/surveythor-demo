<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Survey;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;

class SurveyRepository
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
     * @param int $id
     *
     * @return Survey
     * @throws EntityNotFoundException
     */
    public function find($id)
    {
        $survey = $this->getManager()->find(Survey::class, $id);
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
        return $this
            ->getManager()
            ->createQueryBuilder()
            ->from(Survey::class, 's')
            ->select('s')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Survey $survey
     */
    public function save(Survey $survey)
    {
        $this->getManager()->persist($survey);
        $this->getManager()->flush($survey);
    }

    /**
     * @return EntityManager
     */
    private function getManager()
    {
        /** @var EntityManager $manager */
        $manager = $this->registry->getManagerForClass(Survey::class);

        return $manager;
    }
}
