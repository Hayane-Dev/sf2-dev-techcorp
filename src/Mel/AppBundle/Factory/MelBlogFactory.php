<?php

namespace Mel\AppBundle\Factory;

use Mel\AppBundle\Entity\Article;
use Mel\AppBundle\Entity\Commentaire;
use Mel\AppBundle\Entity\Image;
use Mel\AppBundle\Manager\MelBlogManager;
use Mel\AppBundle\Exception\CategorieNotFoundException;
use Mel\AppBundle\Exception\CategorieNotDefinedException;


class MelBlogFactory
{
  private $melblogmanager;

  public function __construct(MelBlogManager $melblogmanager) {
    $this->melblogmanager = $melblogmanager;
  }

  public function createArticle($data) {
    return $this->hydrateArticle(new Article(), $data);
  }

  public function hydrateArticle(Article $article, $data) {
    $article->setTitle($data->title);
    $article->setAuthor($data->author);
    $article->setContent($data->content);
    if ($data->image) {
      $image = $this->createImage($data->image);
      $article->setImage($image);
    }
    if ($data->categories) {
      foreach ($data->categories as $item) {
        $category = $this->melblogmanager->getCategory($item->name);
        if ($category) {
          $article->addCategory($category);
        } else {
          throw new CategorieNotFoundException();
        }
      }
    } else {
      throw new CategorieNotDefinedException();
    }

    return $article;
  }

  public function createImage($data) {
    $image = new Image();
    $image->setUrl($data->url);
    $image->setAlt($data->alt);

    return $image;
  }

  public function hydrateImage(Image $image, $data) {

  }

  public function createCommentaire($data) {
    $commentaire = new Commentaire();

    return $commentaire;
  }

  public function hydrateCommentaire(Commentaire $commentaire, $data) {

  }
}