<?php

namespace TechCorp\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StaticController extends Controller {

  /**
   * @Route("/", options={"expose"=true}, name="tech_corp_front_homepage")
   */
  public function homePageAction() {
    return $this->render("TechCorpFrontBundle:Static:home-page.html.twig", [
      'home' => 'active',
      // 'about' => ''
    ]);
  }

  public function aboutAction() {
    return $this->render("TechCorpFrontBundle:Static:about.html.twig", [
      // 'home' => '',
      'about' => 'active'
    ]);
  }

  // public function menuAction() {
  //   return $this->render("TechCorpFrontBundle:Static:menu.html.twig", ['items' => [
  //     'home' => $this->generateUrl('tech_corp_front_homepage'),
  //     'about' => $this->generateUrl('tech_corp_front_about')
  //   ]]);
  // }
}