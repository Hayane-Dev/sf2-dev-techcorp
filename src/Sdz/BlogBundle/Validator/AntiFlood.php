<?php 

namespace Sdz\BlogBundle\Validator;

use Symfony\Component\Validator\Constraint;

/** 
 * @Annotation
 */
class AntiFlood extends Constraint {
	// public $message = "Vous avez déjà posté un article il y a moins de 15 secondes, merci d'attendre un peu !";
	// Avec paramètre
	public $message = "Votre paramètre %string% est considéré comme flood.";
}

// L'annotation @Annotation est nécessaire pour que cette nouvelle contrainte soit disponible via les annotations dans les autres classes.
// Les options de l'annotation correspondent aux attributs publics de la classe d'annotation.
// On pourra donc faire @Annotation(message="Mon message personnalisé")

?>