<?php

namespace AppBundle\Repository;

use AppBundle\Entity\AllowedOrigin;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;

/**
 * AllowedOriginRepository
 */
class AllowedOriginRepository extends EntityRepository
{
    /**
     * @param $originName
     *
     * @return AllowedOrigin
     * @throws EntityNotFoundException
     */
    public function findOneActiveByOriginName($originName)
    {
        /** @var AllowedOrigin $allowedOrigin */
        $allowedOrigin = $this->findOneBy(['originName' => $originName, 'isActive' => true]);

        if (is_null($allowedOrigin)) {
            throw new EntityNotFoundException(
                sprintf('active allowedOrigin not found for originName "%s"', $originName)
            );
        }

        return $allowedOrigin;
    }

    /**
     * @param AllowedOrigin $allowedOrigin
     */
    public function persist(AllowedOrigin $allowedOrigin)
    {
        $this->_em->persist($allowedOrigin);
    }

    /**
     * @param AllowedOrigin $allowedOrigin
     */
    public function save(AllowedOrigin $allowedOrigin)
    {
        $this->_em->persist($allowedOrigin);
        $this->_em->flush();
    }

    /**
     * @param AllowedOrigin $allowedOrigin
     */
    public function remove(AllowedOrigin $allowedOrigin)
    {
        $this->_em->remove($allowedOrigin);
        $this->_em->flush();
    }
}
