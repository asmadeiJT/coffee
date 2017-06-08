<?php

namespace IngredientBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use IngredientBundle\Entity\Ingredient;
use IngredientBundle\Form\AddIngredient;

class DefaultController extends Controller
{
    /**
     * @Route("/ingredient")
     */
    public function indexAction()
    {
        return $this->render('IngredientBundle:Default:index.html.twig');
    }

    /**
     * @Route("/ingredient/add", name="add_ingredient")
     */
    public function addAction(Request $request) {
        $ingredient = new Ingredient();
        $form = $this->createForm(AddIngredient::class, $ingredient);
        $formView = $form->createView();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $ingredient->setName($data->getName());
            $ingredient->setCost($data->getCost());

            $em = $this->getDoctrine()->getManager();

            $em->persist($ingredient);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('IngredientBundle:Default:add/ingredient.html.twig', array(
            'base_dir'  => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'form'      => $formView
        ));
    }
}
