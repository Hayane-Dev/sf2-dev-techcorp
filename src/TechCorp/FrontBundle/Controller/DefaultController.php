<?php

namespace TechCorp\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('TechCorpFrontBundle:Default:index.html.twig');
    }

    public function testAction($name)
    {
        return $this->render('TechCorpFrontBundle:Default:test.html.twig', array('name' => $name));
    }
}
