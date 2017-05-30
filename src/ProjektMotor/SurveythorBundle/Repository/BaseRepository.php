<?php
namespace PM\SurveythorBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * BaseRepository
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class BaseRepository extends EntityRepository
{
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
