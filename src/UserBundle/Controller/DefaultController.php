<?php

namespace UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;
use UserBundle\Entity\Credit;
use UserBundle\Form\AddUser;
use UserBundle\Form\AddCredit;

class DefaultController extends Controller
{
    /**
     * @Route("/user/add", name="add_user")
     */
    public function addAction(Request $request) {
        $user = new User();
        $form = $this->createForm(AddUser::class, $user);
        $formView = $form->createView();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $user->setName($data->getName());
            $user->setType($data->getType());
            $user->setAmortization($data->getAmortization());

            $em = $this->getDoctrine()->getManager();

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('UserBundle:Default:add/user.html.twig', array(
            'base_dir'  => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'form'      => $formView
        ));
    }

    /**
     * @Route("/user/credit/add", name="add_user_credit")
     */
    public function addUserCredit(Request $request) {
        $credit = new Credit();
        $form = $this->createForm(AddCredit::class, $credit);
        $formView = $form->createView();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $credit->setUserId($data->getUserId()->getId());
            $credit->setValue($data->getValue());

            $em = $this->getDoctrine()->getManager();

            $em->persist($credit);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('UserBundle:Default:add/credit.html.twig', array(
            'base_dir'  => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'form'      => $formView
        ));
    }
}