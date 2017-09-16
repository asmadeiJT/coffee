<?php
namespace UserBundle\Service;

class Charts
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

    /**
     * Structure chart data
     * @userCups array
     * @return array
     */
    public function prepareChartData($userCups)
    {
        $data = array();
        foreach ($userCups as $cup) {
            $date = $cup->getCreateDate();
            $format = $date->format('m/d/Y');
            if (isset($results[$format])) {
                $data[$format] += $cup->getCost() / 100;
            } else {
                $data[$format] = $cup->getCost() / 100;
            }
        }

        return $data;
    }
}