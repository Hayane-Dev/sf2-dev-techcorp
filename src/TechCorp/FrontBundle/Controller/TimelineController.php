<?php
namespace TechCorp\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TechCorp\FrontBundle\Entity\Status;

class TimelineController extends Controller {

 public function timelineAction() {
   $em = $this->getDoctrine()->getManager();
   $statuses = $em->getRepository('TechCorpFrontBundle:Status')->findAll();

   return $this->render('TechCorpFrontBundle:Timeline:timeline.html.twig', [
     'statuses' => $statuses,
    //  'home' => '',
    //  'about' => ''
   ]);
 }

 public function userTimelineAction($userId) {
   $em = $this->getDoctrine()->getManager();
   $user = $em->getRepository('TechCorpFrontBundle:User')->findOneById($userId);
   
   if (!$user) {
     $this->createNotFoundException("L'utilisateur n' pas été trouvé.");
   }

   $statuses = $em->getRepository('TechCorpFrontBundle:Status')->findBy([
     'user' => $user,
    //  'deleted' => true
   ]);

   return $this->render('TechCorpFrontBundle:Timeline:user_timeline.html.twig', [
     'user' => $user,
     'statuses' => $statuses
   ]);
 }

}