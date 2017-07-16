<?php
/**
 * Created by PhpStorm.
 * User: Asmadei
 * Date: 15.07.2017
 * Time: 22:49
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction(Request $request)
    {
        // Logging user out.
        $this->get('security.token_storage')->setToken(null);

        // Invalidating the session.
        $session = $request->getSession();
        $session->invalidate();

        // Redirecting user to login page in the end.
        $response = $this->redirectToRoute('homepage');

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // Clearing the cookies.
        $cookieNames = [
            $this->container->getParameter('session.name'),
            $this->container->getParameter('session.remember_me.name'),
        ];
        foreach ($cookieNames as $cookieName) {
            $response->headers->clearCookie($cookieName);
        }

        return $response;
    }
}