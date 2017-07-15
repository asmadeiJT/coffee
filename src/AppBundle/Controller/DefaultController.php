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
        $results    = $this->getHistory();
        $totals     = $this->getTotals();
        $em         = $this->getDoctrine()->getManager();
        $totalSpent = $this->getTotalSpent();
        $bankCash   = $em->getRepository('SettingsBundle:Settings')->findBy(array('name' => 'current_bank_cash'))[0]->getValue();
        $credit     = $this->getCredits();
        $balance    = $bankCash - $credit;
        $profit     = $this->getProfit($balance);


        return $this->render('page/home.html.twig', array(
            'base_dir'      => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'results'       => $results,
            'totals'        => $totals,
            'cups_count'    => $em->getRepository('SettingsBundle:Settings')->findBy(array('name' => 'cups_count'))[0]->getValue(),
            'total_spent'   => array_shift($totalSpent),
            'credit'        => $credit,
            'balance'       => $balance,
            'profit'        => $profit
        ));
    }

    /**
     * Get history of coffee consumption
     */
    private function getHistory() {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();

        $qb->select('u.name', 'a.id', 'a.cost', 'a.createDate')
            ->from('CupBundle\Entity\Cup', 'a')
            ->leftJoin(
                'UserBundle\Entity\User',
                'u',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'a.userId = u.id'
            )
            ->orderBy('a.createDate', 'DESC')
            ->setMaxResults(10);

        return $qb->getQuery()->getResult();
    }

    /**
     * Get totals of coffee consumption
     */
    private function getTotals() {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();

        $qb->select('u.id', 'u.name', 'c.value as credit')
            ->from('UserBundle\Entity\User', 'u')
            ->leftJoin(
                'UserBundle\Entity\Credit',
                'c',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'c.userId = u.id'
            )
            ->groupBy('u.name')
            ->orderBy('u.name', 'DESC');


        return $qb->getQuery()->getResult();
    }

    private function getTotalSpent() {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();

        $qb->select('SUM(c.cost) as totalSpent')
            ->from('CupBundle\Entity\Cup', 'c');


        return $qb->getQuery()->getResult();
    }

    private function getProfit($balance) {
        $em                 = $this->getDoctrine()->getManager();
        $fixedBankCash      = $em->getRepository('SettingsBundle:Settings')->findBy(array('name' => 'fixed_bank_cash'));
        $fixedBankCashValue = array_shift($fixedBankCash);

        $profit = $balance - $fixedBankCashValue->getValue();

        return $profit;
    }

    private function getCredits () {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();

        $qb->select('SUM(c.value) as totalCredit')
            ->from('UserBundle\Entity\Credit', 'c');

        $result = $qb->getQuery()->getResult();

        return $result[0]['totalCredit'];
    }
}
