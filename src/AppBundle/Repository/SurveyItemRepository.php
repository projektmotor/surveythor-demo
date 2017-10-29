<?php

namespace AppBundle\Repository;

use AppBundle\Entity\SurveyItem;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;

class SurveyItemRepository
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
     * @param int|array $id
     *
     * @return SurveyItem
     * @throws EntityNotFoundException
     */
    public function findOneById($id)
    {
        $surveyItem = $this->getManager()->find(SurveyItem::class, $id);

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
        $this->getManager()->detach($surveyItem);
        $this->getManager()->flush();
    }

    /**
     * @param SurveyItem $surveyItem
     */
    public function remove(SurveyItem $surveyItem)
    {
        $this->getManager()->remove($surveyItem);
        $this->getManager()->flush();
    }

    /**
     * @param SurveyItem $surveyItem
     */
    public function save(SurveyItem $surveyItem)
    {
        $this->getManager()->persist($surveyItem);
        $this->getManager()->flush();
    }

    /**
     * @return EntityManager
     */
    private function getManager()
    {
        /** @var EntityManager $manager */
        $manager = $this->registry->getManagerForClass(SurveyItem::class);

        return $manager;
    }
}
