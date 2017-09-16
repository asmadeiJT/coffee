<?php
namespace CupBundle\Service;

class Calculation
{
    /**
     * Calculate ingredients cost
     * @data array
     * @beenCost integer
     * $amortization integer
     * @return integer
     */
    public function calculateCost($data, $beenCost, $amortization)
    {
        $user = $data['user'];
        $userType = $user->getType();
        $ingredients = $data['ingredients'];
        $ingredientsCost = 0;
        $beenCost = $data['choice'] * $beenCost;

        foreach ($ingredients as $ingredient) {
            $ingredientsCost = $ingredientsCost + $ingredient->getCost();
        }

        $cost = $beenCost + $ingredientsCost;

        if ($userType == 'buyer') {
            $cost = $cost + $amortization;
        }

        return $cost;
    }
}