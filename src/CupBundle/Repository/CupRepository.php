<?php

namespace CupBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CupRepository extends EntityRepository
{
    public function getLastCups() {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('u.name', 'a.id', 'a.cost', 'a.createDate')
            ->from('CupBundle\Entity\Cup', 'a')
            ->leftJoin(
                'UserBundle\Entity\User',
                'u',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'a.userId = u.id'
            )
            ->orderBy('a.createDate', 'DESC')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult();
    }

    public function getTotalSpent() {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT SUM(c.cost) as totalSpent FROM CupBundle\Entity\Cup c'
            )
            ->getSingleScalarResult();
    }
}