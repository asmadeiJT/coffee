<?php

namespace IngredientBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use IngredientBundle\Entity\Ingredient;
use IngredientBundle\Entity\Type;
use IngredientBundle\Form\AddIngredient;
use IngredientBundle\Form\AddType;
use IngredientBundle\Form\EditIngredient;
use IngredientBundle\Form\EditType;
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
    public function addIngredientAction(Request $request) {
        $ingredient = new Ingredient();
        $form = $this->createForm(AddIngredient::class, $ingredient);
        $formView = $form->createView();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $ingredient->setName($data->getName());
            $ingredient->setCost($data->getCost());
            $ingredient->setQuantity($data->getQuantity());
            $ingredient->setType($data->getType()->getId());
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
    public function editIngredientAction(Request $request) {
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

            $ingredient->setName($data->getName());
            $ingredient->setCost($data->getCost());
            $ingredient->setQuantity($data->getQuantity());
            $ingredient->setType($data->getType()->getId());
            $ingredient->setIsActive($data->getIsActive());

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
    public function ingredientListAction() {
        $em = $this->getDoctrine()->getManager();
        $amortization = $em->getRepository('SettingsBundle:Settings')->findOneBy(array('name' => 'amortization'))->getValue();
        $beenCost = $em->getRepository('SettingsBundle:Settings')->findOneBy(array('name' => 'been_cost'))->getValue();

        $test = $em->getRepository('IngredientBundle:Ingredient')->findBy(array('isActive' => true));
        return $this->render('IngredientBundle:Default:list/ingredient.html.twig', array(
            'base_dir'      => realpath($this->container->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
            'results'       => $em->getRepository('IngredientBundle:Ingredient')->findBy(array('isActive' => true)),
            'amortization'  => $amortization,
            'been_cost'     => $beenCost
        ));
    }

    /**
     * @Route("ingredient/type/add", name="add_ingredient_type")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function addTypeAction(Request $request) {
        $type = new Type();
        $form = $this->createForm(AddType::class, $type);
        $formView = $form->createView();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $type->setName($data->getName());

            $em = $this->getDoctrine()->getManager();

            $em->persist($type);
            $em->flush();

            return $this->redirectToRoute('ingredient_type_list');
        }

        return $this->render('IngredientBundle:Default:add/type.html.twig', array(
            'base_dir'  => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'form'      => $formView
        ));
    }

    /**
     * @Route("ingredient/type/edit", name="edit_ingredient_type")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editTypeAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $type = $em->getRepository('IngredientBundle:Type')->find($id);
        $form = $this->createForm(EditType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if (!$type) {
                throw $this->createNotFoundException(
                    'No ingredient found for id '.$id
                );
            }

            $type->setName($data['name']);

            $em->persist($type);
            $em->flush();

            return $this->redirectToRoute('ingredient_type_list');
        }

        $form->get('name')->setData($type->getName());

        $formView = $form->createView();

        return $this->render('IngredientBundle:Default:edit/type.html.twig', array(
            'base_dir'  => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'form'      => $formView
        ));
    }

    /**
     * @Route("ingredient/type/list", name="ingredient_type_list")
     */
    public function typeListAction() {
        $em = $this->getDoctrine()->getManager();
        $types = $em->getRepository('IngredientBundle:Type')->findAll();

        return $this->render('IngredientBundle:Default:list/type.html.twig', array(
            'base_dir'      => realpath($this->container->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
            'results'       => $types
        ));
    }
}
