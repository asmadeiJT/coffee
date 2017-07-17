<?php

namespace SettingsBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use SettingsBundle\Entity\Settings;
use SettingsBundle\Form\AddSettings;
use SettingsBundle\Form\EditSettings;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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
     * @Security("has_role('ROLE_ADMIN')")
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

            return $this->redirectToRoute('setting_list');
        }

        return $this->render('SettingsBundle:Default:add/settings.html.twig', array(
            'base_dir'  => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'form'      => $formView
        ));
    }

    /**
     * @Route("/settings/edit", name="edit_setting")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $setting = $em->getRepository('SettingsBundle:Settings')->find($id);
        $form = $this->createForm(EditSettings::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if (!$setting) {
                throw $this->createNotFoundException(
                    'No setting found for id '.$id
                );
            }

            $setting->setName($data['name']);
            $setting->setValue($data['value']);

            $em->flush();

            return $this->redirectToRoute('setting_list');
        }

        $form->get('name')->setData($setting->getName());
        $form->get('value')->setData($setting->getValue());

        $formView = $form->createView();

        return $this->render('SettingsBundle:Default:edit/settings.html.twig', array(
            'base_dir'  => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'form'      => $formView
        ));
    }

    /**
     * @Route("/settings/list", name="setting_list")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function listAction() {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();

        $qb->select('a.id, a.name, a.value')
            ->from('SettingsBundle\Entity\Settings', 'a');

        return $this->render('SettingsBundle:Default:list.html.twig', array(
            'base_dir'  => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'results'   => $qb->getQuery()->getResult()
        ));
    }
}
