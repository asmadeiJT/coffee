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
            'totals'    => $totals
        ));
    }

    /**
     * Get history of coffee consumption
     */
    private function getHistory() {
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
    private function getTotals() {
        $em         = $this->getDoctrine()->getManager();
        $qb         = $em->createQueryBuilder();
        $fromDate   = $em->getRepository('SettingsBundle:Settings')->findBy(array('name' => 'calculation_from'))[0]->getValue();
        $toDate     = $em->getRepository('SettingsBundle:Settings')->findBy(array('name' => 'calculation_to'))[0]->getValue();

        $qb->select('u.name', 'c.value as credit', 'SUM(a.cost) as totalCost')
            ->from('CupBundle\Entity\Cup', 'a')
            ->leftJoin(
                'UserBundle\Entity\User',
                'u',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'a.user_id = u.id'
            )
            ->leftJoin(
                'UserBundle\Entity\Credit',
                'c',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'c.userId = u.id'
            )
            ->where('a.create_date > :from_date AND a.create_date < :to_date')
            ->setParameters(array('to_date' => $toDate, 'from_date' => $fromDate))
            ->groupBy('u.name')
            ->orderBy('u.name', 'DESC');


        return $qb->getQuery()->getResult();
    }
}
