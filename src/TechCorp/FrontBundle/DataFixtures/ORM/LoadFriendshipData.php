<?php

namespace TechCorp\FrontBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TechCorp\FrontBundle\Entity\User;
use TechCorp\FrontBundle\Entity\Status;

class LoadFriendshipData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface  {

  const MAX_NB_FRIENDS = 5;

  /**
   * @var ContainerInterface
   */
  private $container;

  public function setContainer(ContainerInterface $container = null) {
    $this->container = $container;
  }

  // Using Faker
  public function load(ObjectManager $manager) {
    $faker = \Faker\Factory::create();

    for ($i=0; $i < LoadUserData::MAX_NB_USERS; $i++) { 
      $currentUser = $this->getReference('user'.$i);
      $j = 0;
      $nbFriends = rand(0, self::MAX_NB_FRIENDS);

      while ($j<$nbFriends) {
        $currentFriend = $this->getReference('user'.rand(0,9));
        if ($currentUser->canAddFriend($currentFriend)) {
          $currentUser->addFriend($currentFriend);
          ++$j;
        }
      }

      $manager->persist($currentUser);

    }

    $manager->flush();
  }

  public function getOrder() {
    return 3;
  }


}
