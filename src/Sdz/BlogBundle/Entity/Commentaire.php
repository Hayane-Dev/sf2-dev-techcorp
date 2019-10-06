<?php 

namespace Sdz\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sdz\BlogBundle\Entity\Commentaire
 * 
 * @ORM\Table(name="sdz_commentaire")
 * @ORM\Entity(repositoryClass="Sdz\BlogBundle\Entity\CommentaireRepository")
 */
class Commentaire {
    /**
     * @var integer $id
     * 
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $author
     * 
     * @ORM\Column(name="author", type="string", length=255)
     */
    private $author;

    /**
     * @var string $content
     * 
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var datetime $date
     * 
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var Sdz\BlogBundle\Entity\Article $article
     * 
     * @ORM\ManyToOne(targetEntity="Sdz\BlogBundle\Entity\Article", inversedBy="commentaires")
     * @ORM\JoinColumn(nullable=false)
     */
    private $article;

    public function __construct() {
        $this->date = new \DateTime();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return Commentaire
     */
    public function setAuthor($author) {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor() {
        return $this->author;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Commentaire
     */
    public function setContent($content) {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Commentaire
     */
    public function setDate($date) {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * Set article
     *
     * @param \Sdz\BlogBundle\Entity\Article $article
     *
     * @return Commentaire
     */
    public function setArticle(\Sdz\BlogBundle\Entity\Article $article) {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return \Sdz\BlogBundle\Entity\Article
     */
    public function getArticle() {
        return $this->article;
    }
}
