<?php

namespace Five\SeoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('FiveSeoBundle:Default:index.html.twig', array('name' => $name));
    }
}
