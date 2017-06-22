<?php

namespace CupBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use CupBundle\Entity\Cup;
use CupBundle\Form\AddCup;

class DefaultController extends Controller
{
    /**
     * @Route("/cup/add", name="add_cup")
     */
    public function addAction(Request $request) {
        $form     = $this->createForm(AddCup::class);
        $formView = $form->createView();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data       = $form->getData();
            $cup        = new Cup();
            $em         = $this->getDoctrine()->getManager();
            $user       = $data['user'];
            $userType   = $user->getType();

            $beenCost = $data['choice'] * $em->getRepository('SettingsBundle:Settings')->findBy(array('name' => 'been_cost'))[0]->getValue();
            $ingredients = $data['ingredients'];
            $ingredientsCost = 0;

            foreach ($ingredients as $ingredient) {
                $ingredientsCost = $ingredientsCost + $ingredient->getCost();
            }

            $cost = $beenCost + $ingredientsCost;

            if ($userType == 'buyer') {
                $cost = $cost + $em->getRepository('SettingsBundle:Settings')->findBy(array('name' => 'amortization'))[0]->getValue();
            }

            $credit = array_shift($em->getRepository('UserBundle:Credit')->findBy(array('userId' => $user->getId())));
            $creditAmount = $credit->getValue();
            $resultCreditAmount = $creditAmount - $cost;

            $credit->setValue($resultCreditAmount);
            $em->persist($credit);
            $em->flush();

            $cup->setCost($cost);
            $cup->setUserId($user->getId());
            $cup->setCreateDate(new \DateTime());
            $em->persist($cup);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('CupBundle:Default:add/cup.html.twig', array(
            'base_dir'  => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'form'      => $formView
        ));
    }

    /**
     * @Route("/cup/delete", name="delete_cup")
     */
    public function deleteAction(Request $request) {
        $em     = $this->getDoctrine()->getManager();
        $id     = $request->get('id');
        $cup    = $em->getRepository('CupBundle:Cup')->find($id);

        if (!$cup) {
            throw $this->createNotFoundException('No guest found');
        }

        $credit = array_shift($em->getRepository('UserBundle:Credit')->findBy(array('userId' => $cup->getUserId())));
        $creditAmount = $credit->getValue();
        $resultCreditAmount = $creditAmount + $cup->getCost();

        $credit->setValue($resultCreditAmount);
        $em->persist($credit);
        $em->flush();

        $em->remove($cup);
        $em->flush();

        return $this->redirectToRoute('homepage');
    }
}