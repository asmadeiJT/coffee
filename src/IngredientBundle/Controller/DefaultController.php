<?php

namespace IngredientBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use IngredientBundle\Entity\Ingredient;
use IngredientBundle\Form\AddIngredient;
use IngredientBundle\Form\EditIngredient;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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
     * @Security("has_role('ROLE_ADMIN')")
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
            $ingredient->setIsActive($data->getIsActive());

            $em = $this->getDoctrine()->getManager();

            $em->persist($ingredient);
            $em->flush();

            return $this->redirectToRoute('ingredient_list');
        }

        return $this->render('IngredientBundle:Default:add/ingredient.html.twig', array(
            'base_dir'  => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'form'      => $formView
        ));
    }

    /**
     * @Route("/ingredient/edit", name="edit_ingredient")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $ingredient = $em->getRepository('IngredientBundle:Ingredient')->find($id);
        $form = $this->createForm(EditIngredient::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if (!$ingredient) {
                throw $this->createNotFoundException(
                    'No ingredient found for id '.$id
                );
            }

            $ingredient->setName($data['name']);
            $ingredient->setCost($data['cost']);
            $ingredient->setIsActive($data['status']);

            $em->persist($ingredient);
            $em->flush();

            return $this->redirectToRoute('ingredient_list');
        }

        $form->get('name')->setData($ingredient->getName());
        $form->get('cost')->setData($ingredient->getCost());

        $formView = $form->createView();

        return $this->render('IngredientBundle:Default:edit/ingredient.html.twig', array(
            'base_dir'  => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'form'      => $formView
        ));
    }

    /**
     * @Route("/ingredient/list", name="ingredient_list")
     */
    public function listAction() {
        $em = $this->getDoctrine()->getManager();
        $amortization = $em->getRepository('SettingsBundle:Settings')->findOneBy(array('name' => 'amortization'))->getValue();
        $beenCost = $em->getRepository('SettingsBundle:Settings')->findOneBy(array('name' => 'been_cost'))->getValue();

        return $this->render('IngredientBundle:Default:list.html.twig', array(
            'base_dir'      => realpath($this->container->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
            'results'       => $em->getRepository('IngredientBundle:Ingredient')->getAllIngredients(),
            'amortization'  => $amortization,
            'been_cost'     => $beenCost
        ));
    }
}
