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
            $em     = $this->getDoctrine()->getManager();
            $data   = $form->getData();
            $userId = $data->getUserId()->getId();
            $userCredit = $em->getRepository('UserBundle:Credit')->findBy(array('userId' => $userId));
            if (isset($userCredit[0])) {
                $userCredit = $userCredit[0];
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

            return $this->redirectToRoute('credit_list');
        }

        return $this->render('UserBundle:Default:add/credit.html.twig', array(
            'base_dir'  => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'form'      => $formView
        ));
    }

    /**
     * @Route("/user/credit/list", name="credit_list")
     */
    public function creditListAction() {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();

        $qb->select('c.id, c.value, u.name')
            ->from('UserBundle\Entity\Credit', 'c')
            ->leftJoin(
                'UserBundle\Entity\User',
                'u',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'c.userId = u.id'
            );

        return $this->render('UserBundle:Default:credit-list.html.twig', array(
            'base_dir'  => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'results'   => $qb->getQuery()->getResult()
        ));
    }
}