<?php 

namespace Sdz\BlogBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AntiFloodValidator extends ConstraintValidator {
	// to flood = innonder
	public function validate($value, Constraint $constraint) {
		// On considère comme flood tout message de moins de 3 caractères
		if (strlen($value) < 3) {
			// Cette ligne déclenche l'erreur pour le formulaire, avec en argument le message
			// $this->context->addViolation($constraint->message);
			// Avec paramètre
			$this->context->addViolation($constraint->message, ["%string%" => $value]);
		} 
	}
}


?>