<?php

namespace UserBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function getUserCredits()
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('u.id', 'u.name', 'c.value as credit')
            ->from('UserBundle\Entity\User', 'u')
            ->leftJoin(
                'UserBundle\Entity\Credit',
                'c',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'c.userId = u.id'
            )
            ->groupBy('u.name')
            ->orderBy('u.name', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getUserInfo($id)
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('c')
            ->from('CupBundle\Entity\Cup', 'c')
            ->where('c.userId = :userId AND c.createDate >= :date')
            ->setParameters(array('userId' => $id, 'date' => new \DateTime('midnight first day of this month')))
            ->getQuery()
            ->getResult();
    }
}