<?php

namespace UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;
use UserBundle\Entity\Credit;
use UserBundle\Form\AddUser;
use UserBundle\Form\AddCredit;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class DefaultController extends Controller
{
    /**
     * @Route("/user/add", name="add_user")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function addUserAction(Request $request)
    {
        $user = new User();
        $credit = new Credit();
        $userForm = $this->createForm(AddUser::class, $user);
        $formView = $userForm->createView();

        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $data = $userForm->getData();

            $user->setName($data->getName());
            $user->setType($data->getType());
            $user->setAmortization($data->getAmortization());

            $em = $this->getDoctrine()->getManager();

            $em->persist($user);
            $em->flush();

            $credit->setUserId($user->getId());
            $credit->setValue(0);

            $em->persist($credit);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('UserBundle:Default:add/user.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
            'form' => $formView
        ));
    }

    /**
     * @Route("/user/credit/add", name="add_user_credit")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function addUserCredit(Request $request)
    {
        $credit = new Credit();
        $form = $this->createForm(AddCredit::class, $credit);
        $formView = $form->createView();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $data = $form->getData();
            $userId = $data->getUserId()->getId();
            $userCredit = $em->getRepository('UserBundle:Credit')->findOneBy(array('userId' => $userId));
            if ($userCredit) {
                $value = $userCredit->getValue() + $data->getValue();
                $userCredit->setUserId($userId);
                $userCredit->setValue($value);
                $em->persist($userCredit);
            } else {
                $credit->setUserId($userId);
                $credit->setValue($data->getValue());
                $em->persist($credit);
            }
            $em->flush();

            $currentBankCash = $em->getRepository('SettingsBundle:Settings')->findOneBy(array('name' => 'current_bank_cash'));
            $currentBankCashValue = $currentBankCash->getValue() + $data->getValue();

            $currentBankCash->setValue($currentBankCashValue);
            $em->persist($currentBankCash);
            $em->flush();

            return $this->redirectToRoute('credit_list');
        }

        return $this->render('UserBundle:Default:add/credit.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
            'form' => $formView
        ));
    }

    /**
     * @Route("/user/credit/list", name="credit_list")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function creditListAction()
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('UserBundle:Default:credit-list.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
            'results' => $em->getRepository('UserBundle:User')->getUserCredits()
        ));
    }

    /**
     * @Route("/user/info", name="user_info")
     */
    public function userInfoAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $chartService = $this->container->get('user.charts');
        $id = $request->get('id');
        $user = $em->getRepository('UserBundle:User')->find($id);
        $userCups = $em->getRepository('UserBundle:User')->getUserInfo($id);
        $yData = $xData = array();
        $chartData = $chartService->prepareChartData($userCups);

        foreach ($chartData as $key => $value) {
            $xData[] = $key;
            $yData[] = $value;
        }

        $series = array(
            array(
                "data" => $yData,
                "name" => $user->getName(),
                'type' => 'line',
            ),
        );

        $ob = new Highchart();
        $ob->chart->renderTo('linechart');  // The #id of the div where to render the chart
        $ob->title->text($user->getName() . ' Info');
        $ob->xAxis->type('datetime');
        $ob->xAxis->title(array('text' => "Date"));
        $ob->xAxis->categories($xData);
        $ob->yAxis->title(array('text' => "Money, rub"));
        $ob->series($series);

        return $this->render('UserBundle:Default:info.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
            'results' => $chartData,
            'user' => $user,
            'chart' => $ob
        ));
    }
}