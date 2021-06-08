<?php 

namespace Sdz\BlogBundle\DataFixtures\ORM; 

use Doctrine\Common\DataFixtures\FixtureInterface; 
use Doctrine\Common\Persistence\ObjectManager; 
use Sdz\BlogBundle\Entity\Competence; 

class Competences implements FixtureInterface 
{  
    // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager  
    public function load(ObjectManager $manager)  
    {    
        // Liste des noms des compétences à ajouter    
        $names = array('Symfony2', 'Doctrine2', 'ES6', 'Angular', 'VueJs');    
        foreach($names as $i => $name) 
        {      
            // On crée la catégorie     
            $liste_competences[$i] = new Competence();      
            $liste_competences[$i]->setName($name);      
            // On la persiste      
            $manager->persist($liste_competences[$i]);    
        }    
        // On déclenche l'enregistrement    
        $manager->flush();  
    } 
}

?>