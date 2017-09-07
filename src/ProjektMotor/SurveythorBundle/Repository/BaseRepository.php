<?php
namespace PM\SurveythorBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\UnitOfWork;

/**
 * BaseRepository
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class BaseRepository extends EntityRepository
{
    public function refresh($entity)
    {
        $this->_em->refresh($entity);
    }

    public function persist($entity)
    {
        $this->_em->persist($entity);
    }

    public function isManaged($entity)
    {
        return $this->getState($entity) === UnitOfWork::STATE_MANAGED;
    }

    public function getState($entity)
    {
        return $this->_em->getUnitOfWork()->getEntityState($entity);
    }

    public function detach($entity)
    {
        $this->_em->detach($entity);
    }

    public function merge($entity)
    {
        return $this->_em->merge($entity);
    }

    public function save($entity)
    {
        $this->_em->persist($entity);
        $this->_em->flush();
    }

    public function remove($entity)
    {
        $this->_em->remove($entity);
        $this->_em->flush();
    }
}
