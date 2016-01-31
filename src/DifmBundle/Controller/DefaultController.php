<?php

namespace DifmBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('DifmBundle:Default:index.html.twig');
    }
}
