<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $results = $this->getHistory();
        $totals = $this->getTotals();

        return $this->render('page/home.html.twig', array(
            'base_dir'  => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'results'   => $results,
            'totals'    => $totals,
            'totalCost' => array_sum(array_column($totals, 'totalCost'))
        ));
    }

    /**
     * Get history of coffee consumption
     */
    public function getHistory() {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();

        $qb->select('u.name', 'a.id', 'a.cost', 'a.create_date')
            ->from('CupBundle\Entity\Cup', 'a')
            ->leftJoin(
                'UserBundle\Entity\User',
                'u',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'a.user_id = u.id'
            )
            ->orderBy('a.create_date', 'DESC')
            ->setMaxResults(10);

        return $qb->getQuery()->getResult();
    }

    /**
     * Get totals of coffee consumption
     */
    public function getTotals() {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();

        $qb->select('u.name', 'SUM(a.cost) as totalCost')
            ->from('CupBundle\Entity\Cup', 'a')
            ->leftJoin(
                'UserBundle\Entity\User',
                'u',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'a.user_id = u.id'
            )
            ->where('a.create_date > :date')
            ->setParameter('date', new \DateTime('midnight first day of this month'))
            ->groupBy('u.name')
            ->orderBy('totalCost', 'DESC');


        return $qb->getQuery()->getResult();
    }
}
