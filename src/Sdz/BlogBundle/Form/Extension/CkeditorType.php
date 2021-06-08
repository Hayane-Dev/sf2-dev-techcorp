<?php 
namespace Sdz\BlogBundle\Form\Extension;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CkeditorType extends AbstractType {

    public function getParent() {
        return "textarea";
    }

    public function getName() {
        return "ckeditor";
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults([
            "attr" => ["class" => "ckeditor"]
        ]);
    }

}

 ?>

