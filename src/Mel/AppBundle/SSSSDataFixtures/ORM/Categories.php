<?php 

namespace Mel\AppBundle\DataFixtures\ORM; 

use Doctrine\Common\DataFixtures\FixtureInterface; 
use Doctrine\Common\Persistence\ObjectManager; 
use Mel\AppBundle\Entity\Categorie; 

class Categories implements FixtureInterface 
{  
    // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager  
    public function load(ObjectManager $manager)  
    {    
        // Liste des noms de catégorie à ajouter    
        $names = array('Symfony2', 'Doctrine2', 'Tutoriel', 'Évènement');    
        foreach($names as $i => $name) {      
            // On crée la catégorie     
            $liste_categories[$i] = new Categorie();      
            $liste_categories[$i]->setName($name);      
            // On la persiste      
            $manager->persist($liste_categories[$i]);    
        }    
        // On déclenche l'enregistrement    
        $manager->flush();  
    } 
}

?>