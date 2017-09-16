<?php

namespace SettingsBundle\Repository;

use Doctrine\ORM\EntityRepository;

class SettingsRepository extends EntityRepository
{
    public function getAllSettings()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT a.id, a.name, a.value FROM SettingsBundle\Entity\Settings a'
            )
            ->getResult();
    }
}