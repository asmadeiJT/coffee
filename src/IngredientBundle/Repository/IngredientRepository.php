<?php

namespace IngredientBundle\Repository;

use Doctrine\ORM\EntityRepository;

class IngredientRepository extends EntityRepository
{
    public function getAllIngredients()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT a.id, a.name, a.type, a.cost, a.isActive, a.quantity FROM IngredientBundle\Entity\Ingredient a WHERE a.isActive = 1'
            )
            ->getResult();
    }
}