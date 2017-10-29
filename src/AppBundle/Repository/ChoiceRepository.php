<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Choice;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;

class ChoiceRepository
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
     * @param Choice $choice
     */
    public function remove(Choice $choice)
    {
        $this->getManager()->remove($choice);
        $this->getManager()->flush();
    }

    /**
     * @return EntityManager
     */
    private function getManager()
    {
        /** @var EntityManager $manager */
        $manager = $this->registry->getManagerForClass(Choice::class);

        return $manager;
    }
}
