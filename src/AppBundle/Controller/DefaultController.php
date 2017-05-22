<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Cup;
use AppBundle\Form\AddCupForm;

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
            'totalCups' => array_sum(array_column($totals, 'totalCups'))
        ));
    }

    /**
     * @Route("/report", name="report")
     */
    public function generateReportAction(Request $request) {
        $cup = new Cup();
        $form = $this->createForm(AddCupForm::class, $cup);
        $formView = $form->createView();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $cup->setCups($data->getCups());
            $cup->setUserId($data->getUserId());
            $cup->setCreateDate(new \DateTime());

            $em = $this->getDoctrine()->getManager();

            $em->persist($cup);
            $em->flush();
        }

        $results = $this->getHistory();
        $totals = $this->getTotals();

        return $this->render('page/home.html.twig', array(
            'base_dir'  => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'form'      => $formView,
            'results'   => $results,
            'totals'    => $totals,
            'totalCups' => array_sum(array_column($totals, 'totalCups'))
        ));
    }

    /**
     * @Route("/add/cup", name="add_cup")
     */

    public function addCupAction(Request $request) {
        $cup = new Cup();
        $form = $this->createForm(AddCupForm::class, $cup);
        $formView = $form->createView();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $cup->setCups($data->getCups());
            $cup->setUserId($data->getUserId());
            $cup->setCreateDate(new \DateTime());

            $em = $this->getDoctrine()->getManager();

            $em->persist($cup);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('page/add/cup.html.twig', array(
            'base_dir'  => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'form'      => $formView
        ));
    }

    public function getHistory() {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();

        $qb->select('u.name', 'a.cups', 'a.create_date')
            ->from('AppBundle\Entity\Cup', 'a')
            ->leftJoin(
                'AppBundle\Entity\User',
                'u',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'a.user_id = u.id'
            )
            ->orderBy('a.create_date', 'DESC')
            ->setMaxResults(10);

        return $qb->getQuery()->getResult();
    }

    public function getTotals() {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();

        $qb->select('u.name', 'SUM(a.cups) as totalCups')
            ->from('AppBundle\Entity\Cup', 'a')
            ->leftJoin(
                'AppBundle\Entity\User',
                'u',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'a.user_id = u.id'
            )
            ->groupBy('u.name')
            ->orderBy('totalCups', 'DESC');


        return $qb->getQuery()->getResult();
    }
}
