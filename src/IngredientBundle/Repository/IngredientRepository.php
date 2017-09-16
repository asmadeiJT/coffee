<?php

namespace IngredientBundle\Repository;

use Doctrine\ORM\EntityRepository;

class IngredientRepository extends EntityRepository
{
    public function getAllIngredients()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT a.id, a.name, a.cost, a.isActive FROM IngredientBundle\Entity\Ingredient a'
            )
            ->getResult();
    }
}