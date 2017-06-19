<?php

namespace IngredientBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use IngredientBundle\Entity\Ingredient;
use IngredientBundle\Form\AddIngredient;
use IngredientBundle\Form\EditIngredient;

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

    /**
     * @Route("/ingredient/edit", name="edit_ingredient")
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
        $qb = $em->createQueryBuilder();

        $qb->select('a.id, a.name, a.cost, a.isActive')
            ->from('IngredientBundle\Entity\Ingredient', 'a');

        return $this->render('IngredientBundle:Default:list.html.twig', array(
            'base_dir'  => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'results'   => $qb->getQuery()->getResult()
        ));
    }
}
