<?php

namespace SettingsBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use SettingsBundle\Entity\Settings;
use SettingsBundle\Form\AddSettings;

class DefaultController extends Controller
{
    /**
     * @Route("/settings")
     */
    public function indexAction()
    {
        return $this->render('SettingsBundle:Default:index.html.twig');
    }

    /**
     * @Route("/settings/add", name="add_setting")
     */
    public function addAction(Request $request) {
        $setting = new Settings();
        $form = $this->createForm(AddSettings::class, $setting);
        $formView = $form->createView();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $setting->setName($data->getName());
            $setting->setValue($data->getValue());

            $em = $this->getDoctrine()->getManager();

            $em->persist($setting);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('IngredientBundle:Default:add/ingredient.html.twig', array(
            'base_dir'  => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'form'      => $formView
        ));
    }
}
