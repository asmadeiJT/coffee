<?php
namespace UserBundle\Service;

class Charts
{
    /**
     * Structure chart data
     * @param array $userCups
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