<?php

namespace IngredientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/ingredient")
     */
    public function indexAction()
    {
        return $this->render('IngredientBundle:Default:index.html.twig');
    }
}
