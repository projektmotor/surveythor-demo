<?php

namespace PM\SurveythorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('PMSurveythorBundle:Default:index.html.twig');
    }
}
