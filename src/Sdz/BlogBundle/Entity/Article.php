<?php

namespace Sdz\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Article
 *
 * @ORM\Table(name="sdz_article")
 * @ORM\Entity(repositoryClass="Sdz\BlogBundle\Entity\ArticleRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Article
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=255)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var bool
     * @ORM\Column(name="published", type="boolean")
     */
    private $published;

    /**
     * @var Sdz\BlogBundle\Entity\Image
     * 
     * @ORM\OneToOne(targetEntity="Sdz\BlogBundle\Entity\Image", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $image;

    /**
     * @ORM\ManyToMany(targetEntity="Sdz\BlogBundle\Entity\Categorie", cascade={"persist"})
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity="Sdz\BlogBundle\Entity\Commentaire", mappedBy="article")
     */
    private $commentaires;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="date_edition", type="datetime", nullable=true)
     */
    private $dateEdition;

    /** 
     * @var string 
     * 
     * @Gedmo\Slug(fields={"title"}) 
     * @ORM\Column(length=128, unique=true) 
     */
    private $slug;

    /**
     * Constructeur
     */
    public function __construct() {
        // Par défaut la date de l'article est la date du jour 
        $this->date = new \Datetime();
        $this->published = true;
        // ArrayCollection
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->commentaires = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Article
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
     * Set title
     *
     * @param string $title
     *
     * @return Article
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return Article
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
     * @return Article
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
     * Set published
     *
     * @param boolean $published
     *
     * @return Article
     */
    public function setPublished($published) {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished() {
        return $this->published;
    }

    /**
     * Set image
     *
     * @param \Sdz\BlogBundle\Entity\Image $image
     *
     * @return Article
     */
    public function setImage(\Sdz\BlogBundle\Entity\Image $image = null) {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \Sdz\BlogBundle\Entity\Image
     */
    public function getImage() {
        return $this->image;
    }

    /**
     * Add category
     *
     * @param \Sdz\BLogBundle\Entity\Categorie $category
     *
     * @return Article
     */
    public function addCategory(\Sdz\BLogBundle\Entity\Categorie $category) {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \Sdz\BLogBundle\Entity\Categorie $category
     */
    public function removeCategory(\Sdz\BLogBundle\Entity\Categorie $category) {
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories() {
        return $this->categories;
    }

    /**
     * Add commentaire
     *
     * @param \Sdz\BlogBundle\Entity\Commentaire $commentaire
     *
     * @return Article
     */
    public function addCommentaire(\Sdz\BlogBundle\Entity\Commentaire $commentaire) {
        $this->commentaires[] = $commentaire;

        // Découle du cadre bidirectionnelle de la relation
        // Pour garder la cohérence il faut aussi setter l'article dans l'objet Commentaire
        $commentaire->setArticle($this);

        return $this;
    }

    /**
     * Remove commentaire
     *
     * @param \Sdz\BlogBundle\Entity\Commentaire $commentaire
     */
    public function removeCommentaire(\Sdz\BlogBundle\Entity\Commentaire $commentaire) {
        $this->commentaires->removeElement($commentaire);
    }

    /**
     * Get commentaires
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommentaires() {
        return $this->commentaires;
    }

    /**
     * Set dateEdition
     *
     * @param \DateTime $dateEdition
     *
     * @return Article
     */
    public function setDateEdition($dateEdition) {
        $this->dateEdition = $dateEdition;

        return $this;
    }

    /**
     * Get dateEdition
     *
     * @return \DateTime
     */
    public function getDateEdition() {
        return $this->dateEdition;
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateDate() {
        $this->setDateEdition(new \Datetime());
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Article
     */
    public function setSlug($slug) {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug() {
        return $this->slug;
    }
}
