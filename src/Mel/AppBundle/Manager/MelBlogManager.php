<?php

namespace Mel\AppBundle\Manager;

// use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
// Try with the EntityMangerInterface ??? Not implemented in 2.7 version ?
use Mel\AppBundle\Entity\Article;

/**
 * Manager d'articles du Mel Blog
 */
class MelBlogManager
{
  // private $articleEntityRepository;
  // private $commentEntityRepository;
  // private $categoryEntityRepository;
  private $_em;

  public function __construct(
    // EntityRepository $articleEntityRepository, 
    // EntityRepository $commentEntityRepository, 
    // EntityRepository $categoryEntityRepository
    EntityManager $em
  )
  {
    // $this->articleEntityRepository = $articleEntityRepository;
    // $this->commentEntityRepository = $commentEntityRepository;
    // $this->categoryEntityRepository = $categoryEntityRepository;
    $this->_em = $em;
  }

  public function getAllArticles() {
    // return $this->articleEntityRepository->findAll();
    return $this->_em->getRepository("MelAppBundle:Article")->findAll();

  }

  public function getArticleById($id) {
    // return $this->articleEntityRepository->find($id);
    return $this->_em->getRepository("MelAppBundle:Article")->find($id);
  }

  public function getCommentsByArticle($articleId) {
    // $article = $this->articleEntityRepository->find($articleId);
    $article = $this->_em->getRepository("MelAppBundle:Article")->find($articleId);

    // return $this->commentEntityRepository->findByArticle($article);
    return $this->_em->getRepository("MelAppBundle:Commentaire")->findByArticle($article);
  }

  public function getAllComments() {
    // return $this->commentEntityRepository->findAll();
    return $this->_em->getRepository("MelAppBundle:Commentaire")->findAll();
  }

  public function getCategory($name) {
    // return $this->categoryEntityRepository->findByName($name);
    // return $this->categoryEntityRepository->findOneBy(array(
    //   'name' => $name
    // ));
    return $this->_em->getRepository('\Mel\AppBundle\Entity\Categorie')->findOneBy(array(
      'name' => $name
    ));
  }

 public function addArticle(Article $article) {
  //  $this->articleEntityRepository->persist($article);
  //  $this->articleEntityRepository->flush(); => Don't work !!!
  $this->_em->persist($article);
  $this->_em->flush();
 }

}