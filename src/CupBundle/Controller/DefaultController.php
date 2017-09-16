<?php

namespace CupBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use CupBundle\Entity\Cup;
use CupBundle\Form\AddCup;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class DefaultController extends Controller
{
    /**
     * @Route("/cup/add", name="add_cup")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function addAction(Request $request)
    {
        $form = $this->createForm(AddCup::class);
        $calculation = $this->container->get('cup.calculation');
        $formView = $form->createView();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $cup = new Cup();
            $em = $this->getDoctrine()->getManager();
            $user = $data['user'];
            $beenCost = $em->getRepository('SettingsBundle:Settings')->findOneBy(array('name' => 'been_cost'))->getValue();
            $amortization = $em->getRepository('SettingsBundle:Settings')->findOneBy(array('name' => 'amortization'))->getValue();
            $cupsCount = $em->getRepository('SettingsBundle:Settings')->findOneBy(array('name' => 'cups_count'));
            $cupsCountValue = $cupsCount->getValue() + $data['choice'];
            $cost = $calculation->calculateCost($data, $beenCost, $amortization);

            $credit = $em->getRepository('UserBundle:Credit')->findOneBy(array('userId' => $user->getId()));
            $resultCreditAmount = $credit->getValue() - $cost;

            $cupsCount->setValue($cupsCountValue);
            $em->persist($cupsCount);
            $em->flush();

            $credit->setValue($resultCreditAmount);
            $em->persist($credit);
            $em->flush();

            $cup->setCost($cost);
            $cup->setIsLong($data['choice'] > 1 ? true : false);
            $cup->setUserId($user->getId());
            $cup->setCreateDate(new \DateTime());
            $em->persist($cup);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('CupBundle:Default:add/cup.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
            'form' => $formView
        ));
    }

    /**
     * @Route("/cup/delete", name="delete_cup")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $cup = $em->getRepository('CupBundle:Cup')->find($id);

        if (!$cup) {
            throw $this->createNotFoundException('No guest found');
        }

        $credit = $em->getRepository('UserBundle:Credit')->findOneBy(array('userId' => $cup->getUserId()));
        $resultCreditAmount = $credit->getValue() + $cup->getCost();
        $currentCupCount = $cup->getIsLong() ? 2 : 1;
        $cupsCount = $em->getRepository('SettingsBundle:Settings')->findOneBy(array('name' => 'cups_count'));
        $cupsCountValue = $cupsCount->getValue() - $currentCupCount;

        $cupsCount->setValue($cupsCountValue);
        $em->persist($cupsCount);
        $em->flush();

        $credit->setValue($resultCreditAmount);
        $em->persist($credit);
        $em->flush();

        $em->remove($cup);
        $em->flush();

        return $this->redirectToRoute('homepage');
    }
}