<?php 

namespace Sdz\BlogBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;

// class SdzAntispam {
class SdzAntispam extends \Twig_Extension {

    protected $doctrine;
    protected $locale;
    protected $nbFoundedForSpam;

    // public function __construct(Registry $doctrine, $locale, $nbFoundedForSpam) {
    public function __construct(Registry $doctrine, $nbFoundedForSpam) {
        $this->doctrine = $doctrine;
        // $this->locale = (string) $locale;
        $this->nbFoundedForSpam = (int) $nbFoundedForSpam;
    }

    // C'est cette fonction qui va être exécutée par le call
    public function setlocale($locale) {
        $this->locale = $locale;
    }

    public function getName() {
        return "SdzAntispam";
    }

    public function getFunctions() {
        return [
            "antispam_check" => new \Twig_Function_Method($this, "isSpam")
        ];
    }

    /**
     * Vérifie si le texte est un spam ou non.
     * Un texte est considéré comme spam s'il contient 3 liens ou adresses mails 
     * 
     * @param string $text
     * 
     * @return bool 
     */
    public function isSpam($text) {
        // return ($this->countLinks($text) + $this->countMails($text) >= 3) ? true : false;
        return ($this->countLinks($text) + $this->countMails($text) >= $this->nbFoundedForSpam) ? true : false;
    }

    /**
     * Compte les urls de $text
     * 
     * @param string $text
     * 
     * @return int 
     */    
    private function countLinks($text) {
        $pattern = '#(http|https|ftp)://([A-Z0-9][A-Z0-9_-]*(?:.[A-Z0-9][AZ0-9_-]*)+):?(d+)?/?#i';
        preg_match_all($pattern, $text, $matches); 

        return count($matches[0]); 
    }

    /**
     * Compte les emails de $text
     * 
     * @param string $text
     * 
     * @return int 
     */
    private function countMails($text) {
        $pattern = '#[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}#i';
        preg_match_all($pattern, $text, $matches);

        return count($matches[0]); 
    }
}

?>