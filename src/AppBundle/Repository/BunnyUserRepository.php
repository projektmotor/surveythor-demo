<?php

namespace AppBundle\Repository;

use AppBundle\Entity\BunnyUser;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;

class BunnyUserRepository
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
     * @return BunnyUser[]
     */
    public function findAll(): array
    {
        return $this
            ->getManager()
            ->createQueryBuilder()
            ->from(BunnyUser::class, 'bunny_user')
            ->select('s')
            ->getQuery()
            ->getResult();
    }

    public function save(BunnyUser $bunnyUser)
    {
        $this->getManager()->persist($bunnyUser);
        $this->getManager()->flush();
    }

    /**
     * @return EntityManager
     */
    private function getManager()
    {
        /** @var EntityManager $manager */
        $manager = $this->registry->getManagerForClass(BunnyUser::class);

        return $manager;
    }
}
