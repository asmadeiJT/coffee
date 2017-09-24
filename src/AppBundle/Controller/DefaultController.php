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
        $userCredits = $this->getUserCredits();
        $totalSpent = $this->getTotalSpent();
        $bankCash = $this->getSetting('current_bank_cash');
        $credit = $this->getCredits();
        $balance = $bankCash - $credit;
        $profit = $this->getProfit($balance);


        return $this->render('page/home.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
            'results' => $results,
            'totals' => $userCredits,
            'cups_count' => $this->getSetting('cups_count'),
            'total_spent' => $totalSpent,
            'credit' => $credit,
            'balance' => $balance,
            'profit' => $profit
        ));
    }

    /**
     * Get history of coffee consumption
     * @param $name string
     * @return string
     */
    private function getSetting($name)
    {
        $em = $this->getDoctrine()->getManager();

        return $em->getRepository('SettingsBundle:Settings')->findOneBy(array('name' => $name))->getValue();
    }

    /**
     * Get history of coffee consumption
     * @return array
     */
    private function getHistory()
    {
        $em = $this->getDoctrine()->getManager();

        return $em->getRepository('CupBundle:Cup')
            ->getLastCups();
    }

    /**
     * Get totals of coffee consumption
     * @return array
     */
    private function getUserCredits()
    {
        $em = $this->getDoctrine()->getManager();

        return $em->getRepository('UserBundle:User')
            ->getUserCredits();
    }

    /**
     * Get total spent
     * @return string
     */
    private function getTotalSpent()
    {
        $em = $this->getDoctrine()->getManager();

        return $em->getRepository('CupBundle:Cup')
            ->getTotalSpent();
    }

    /**
     * Get total spent
     * @return integer
     */
    private function getProfit($balance)
    {
        $fixedBankCash = $this->getSetting('fixed_bank_cash');
        $profit = $balance - $fixedBankCash;

        return $profit;
    }

    /**
     * Get total credits
     * @return string
     */
    private function getCredits()
    {
        $em = $this->getDoctrine()->getManager();

        return $em->getRepository('UserBundle:Credit')
            ->getTotalCredits();
    }
}
