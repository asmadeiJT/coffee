<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Form\AddUser;

class UserController extends Controller
{
    /**
     * @Route("/user/add", name="add_user")
     */
    public function addAction(Request $request) {
        $cup = new User();
        $form = $this->createForm(AddUser::class, $cup);
        $formView = $form->createView();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $cup->setName($data->getName());

            $em = $this->getDoctrine()->getManager();

            $em->persist($cup);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('page/add/user.html.twig', array(
            'base_dir'  => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'form'      => $formView
        ));
    }
}