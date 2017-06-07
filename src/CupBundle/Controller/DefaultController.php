<?php

namespace CupBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
        $cup        = new Cup();
        $form       = $this->createForm(AddCup::class, $cup);
        $formView   = $form->createView();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $cost = $data->getCost() * 28;
            $cup->setCost($cost);
            $cup->setUserId($data->getUserId()->getId());
            $cup->setCreateDate(new \DateTime());

            $em = $this->getDoctrine()->getManager();

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

        $em->remove($cup);
        $em->flush();

        return $this->redirectToRoute('homepage');
    }
}