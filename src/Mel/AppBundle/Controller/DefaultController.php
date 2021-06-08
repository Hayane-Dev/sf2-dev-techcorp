<?php

namespace Mel\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction() {
        return $this->render('MelAppBundle:Default:index.html.twig');
    }

    public function helloAction($name) {
        return $this->render('MelAppBundle:Default:hello.html.twig', array('name' => $name));
    }

    public function salamAction() {
        return $this->render('MelAppBundle:Default:salam.html.twig');
    }
}
