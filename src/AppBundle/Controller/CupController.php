<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Cup;
use AppBundle\Form\AddCup;

class CupController extends Controller
{
    /**
     * @Route("/cup/add", name="add_cup")
     */
    public function addAction(Request $request) {
        $cup = new Cup();
        $form = $this->createForm(AddCup::class, $cup);
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
}