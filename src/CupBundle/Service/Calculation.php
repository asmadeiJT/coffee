<?php
namespace CupBundle\Service;

use Doctrine\ORM\EntityManager;

class Calculation
{
    /**
     * Calculate ingredients cost
     * @param EntityManager $em
     * @param array $data
     * @param integer $beenCost
     * @param integer $amortization
     * @return integer
     */
    public function calculateCost($em, $data, $beenCost, $amortization)
    {
        $user = $data['user'];
        $userType = $user->getType();
        $ingredients = $data['ingredients'];
        $ingredientsCost = 0;
        $beenCost = $data['choice'] * $beenCost;

        foreach ($ingredients as $ingredient) {
            $ingredientsCost = $ingredientsCost + $ingredient->getCost();
            if ($ingredient->getQuantity()) {
                $ingredient->setQuantity($ingredient->getQuantity() - 1);
                $em->persist($ingredient);
                $em->flush();
            }
        }

        $cost = $beenCost + $ingredientsCost;

        if ($userType == 'buyer') {
            $cost = $cost + $amortization;
        }

        return $cost;
    }
}