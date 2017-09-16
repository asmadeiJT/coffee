<?php

namespace UserBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CreditRepository extends EntityRepository
{
    public function getTotalCredits()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT SUM(c.value) as totalCredit FROM UserBundle\Entity\Credit c'
            )
            ->getSingleScalarResult();
    }
}