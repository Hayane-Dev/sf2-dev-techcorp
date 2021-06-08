<?php

namespace Mel\AppBundle\Controller;

use Mel\AppBundle\Entity\Article;
use Mel\AppBundle\Entity\Commentaire;
use Mel\AppBundle\Entity\Image;
use Mel\AppBundle\Exception\CategorieNotDefinedException;
use Mel\AppBundle\Exception\CategorieNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class MelController extends Controller 
{
    public function test1Action() 
    {
        $tag = false;
        $req = $this->get('request');
        $tag = $req->query->get('tag');
        if ($tag) {
          return new Response('Un tag est présent dans l\'url, sa valeur est: '.$tag);
        }

        // Using flash messages
        $this->get('session')->getFlashBag()->add('info', 'test effectué avec succès !');

        // return new Response('Hi Mel, how are you today ?');
        return $this->render('MelAppBundle:Mel:test1.html.twig', array('name' => 'HAYANI'));
    }

    public function test2Action($txt) 
    {
      return new Response('3aleikoum salam !!! '. $txt);
    }

    public function test3Action()
    {
      // $url = $this->generateUrl('mel_app_test4', array('id' => 19));
      $url = $this->generateUrl('mel_app_test4', array('id' => 19), true);

      return $this->redirect($url);
    }

    public function test4Action($id)
    {
      return new Response('Redirection ok pour id: '.$id);
    }

    public function test5Action($id)
    {
      $res = new Response(json_encode(array('id' => $id)));
      $res->headers->set('Content-Type', 'application/json');

      return $res;
    }

    // Sending Emails
    public function test6Action()
    {
      $mailer = $this->get('mailer');
      // SwitfMailer
      $message = \Swift_Message::newInstance()
        ->setSubject('Hello Mel')
        ->setFrom('meldev2021@gmail.com')
        ->setTo('melkorchi80@gmail.com')
        ->setBody('Hi Mel how are you bro ?');

      $mailer->send($message);

      return new Response('Votre email a bien été envoyé !');
    }
    
    // Actions du MelBlog

    public function indexAction($page) 
    {
      if ($page < 1 && $page != '')
      {
        // Testing the number page and throwing if needed an exception (NotFoundHttpException)
        throw $this->createNotFoundException('La page '. $page .' n\'existe pas !');
      }

      // Dsiplaying the list of articles
      // return $this->render('MelAppBundle:Mel:index.html.twig', array(
      //   'page' => $page, 
      //   'articles' => array(
      //     array('id' => 1, 'title' => 'First article', 'author' => '@MEL', 'date' => '2020/10/05', 'content' => 'Now here is the first article content !'),
      //     array('id' => 2, 'title' => 'Second article', 'author' => '@MEL', 'date' => '2020/10/06', 'content' => 'Now here is the second article content !'),
      //     array('id' => 3, 'title' => 'Third article', 'author' => '@MEL', 'date' => '2020/10/07', 'content' => 'Now here is the third article content !'),
      //     array('id' => 4, 'title' => 'Fourth article', 'author' => '@MEL', 'date' => new \DateTime(), 'content' => 'Now here is the fourth article content !')
      //   )
      // ));
      $em = $this->getDoctrine()->getManager();
      $list_articles = $em->getRepository('Mel\AppBundle\Entity\Article')->findAll();
      // dump($list_articles);die;

      return $this->render('MelAppBundle:Mel:index.html.twig', array('articles' => $list_articles));
    }

    public function viewAction($id) 
    {
      // Displaying the article with the passed id...
      // $article = array(
      //   'id' => $id,
      //   'title' => 'Article test',
      //   'author' => '@MEL',
      //   'date' => new \DateTime(),
      //   'content' => 'Now here is the article test content !'
      // );

      // Using Repository to get entities from database
      $em = $this->getDoctrine()->getManager();

      // $article = $em->getRepository('MelAppBundle:Article')->find(1);
      // dump($article);die;

      $article = $em->getRepository('MelAppBundle:Article')->find($id);

      if ($article === null) {
        throw $this->createNotFoundException('Article[id='.$id.'] inexistant.');
      }

      return $this->render('MelAppBundle:Mel:view.html.twig', array('article' => $article));
    }

    public function addAction() 
    {
      $article = new Article();
      $article->setTitle('Jeet Kune Do');
      $article->setAuthor('@Logimek');
      $article->setContent('La maîtrise de soi est un préalable obligatoire à l\'accession de la liberté !!!');

      $image = new Image();
      $image->setUrl('https://jeet-kune-do.com');
      $image->setAlt('jeet-kune-do');

      $article->setImage($image);

      $comment1 = new Commentaire();
      $comment1->setAuthor('@Mel');
      $comment1->setContent('This book is amazing !!!');
      $comment1->setArticle($article);

      $comment2 = new Commentaire();
      $comment2->setAuthor('@Mel');
      $comment2->setContent('You have to read it !!!');
      $comment2->setArticle($article);

      $em = $this->getDoctrine()->getManager();
      $em->persist($article);
      $em->persist($comment1);
      $em->persist($comment2);
      $em->flush();

      // Testing if a form was been submitted
      if ($this->get('request')->getMethod() == 'POST')
      {
        $this->get('session')->getFlashBag()->add('notice', 'Article '.$article->getTitle().' bien enregistré !');

        return $this->redirect($this->generateUrl('melblog_view', array('id' => 19 )));
      }

      // Displaying the form to add an article
      return $this->render('MelAppBundle:Mel:add.html.twig');
    }

    public function editAction($id)
    {
      // $id = 19;
      $em = $this->getDoctrine()->getManager();
      $article = $em->getRepository('Mel\AppBundle\Entity\Article')->find($id);

      if ($article === null) {
        throw $this->createNotFoundException('Article[id='.$id.' inexistant.');
      }

      $list_categories = $em->getRepository('Mel\AppBundle\Entity\Categorie')->findAll();
      foreach ($list_categories as $key => $category) {
        $article->addCategory($category);
      }
      $em->flush();

      return $this->render('MelAppBundle:Mel:edit.html.twig', array('id' => $id));
    }

    public function deleteAction($id)
    {
      $id = 19;
      return $this->render('MelAppBundle:Mel:delete.html.twig', array('id' => $id));
    }

    public function viewSlugAction($slug, $year, $format)
    {
      return new Response('Slug: '.$slug.' année: '.$year.' format: '.$format);
    }

    public function menuAction($number)
    {
      $list = array(
        array('id' => 11, 'title' => 'Mes dernières vacances'),
        array('id' => 13, 'title' => 'Mon dernier week-end'),
        array('id' => 15, 'title' => 'La séance de sport')
      );

      return $this->render('MelAppBundle:Mel:menu.html.twig', array('list_articles' => $list));
    }

    /**
     * Méthode test illustration d'une relation OneToOne Article Image 
     * Modification d'une image associée à un article
     */
    public function editImageAction($article_id) {
      $em = $this->getDoctrine()->getManager();
      $article = $em->getRepository('MelAppBundle:Article')->find($article_id);
      // $article = $em->getRepository('Mel\AppBundle\Entity\Article')->find($article_id);
      $image = $article->getImage();
      $image->setUrl('https://www.bankofimages/img212.png');

      $em->flush();

      return new Response('L\'image de l\'article a bien été modifé !!!');
    }

    public function getAllArticlesAction() {
      $em = $this->getDoctrine()->getManager();
      $list = $em->getRepository('Mel\AppBundle\Entity\Article')->findAll();

      return new JsonResponse($list);
    }

    public function getAllCommentsAction() {
      $em = $this->getDoctrine()->getManager();
      $comments = $em->getRepository('Mel\AppBundle\Entity\Commentaire')->findAll();

      return new JsonResponse($comments);
    }

    // Getting data using a Manager as service, return JsonResponse (API)

    /**
     * All articles
     *
     * @return void
     */
    public function getArticlesAction() {
      return new JsonResponse($this->get('mel_app.melblogmanager')->getAllArticles());
    }

    /**
     * An article
     *
     * @param [type] $articleId
     * @return void
     */
    public function getArticleAction($articleId) {
      return new JsonResponse($this->get('mel_app.melblogmanager')->getArticleById($articleId));
    }

    /**
     * All comments by article
     *
     * @param [type] $articleId
     * @return void
     */
    public function getCommentsByArticleAction($articleId) {
      return new JsonResponse($this->get('mel_app.melblogmanager')->getCommentsByArticle($articleId));
    }

    /**
     * All comments
     *
     * @return void
     */
    public function getCommentsAction() {
      return new JsonResponse($this->get('mel_app.melblogmanager')->getAllComments());
    }

    public function addArticle__oldAction(Request $request) {
      $payload = json_decode($request->getContent());
      // Création d'un objet Article
      $article = new Article();
      // Hydratation de l'objet
      $article->setTitle($payload->title);
      $article->setAuthor($payload->author);
      $article->setContent($payload->content);
      $image = new Image();
      $image->setUrl($payload->image->url);
      $image->setAlt($payload->image->alt);
      $article->setImage($image);
      foreach ($payload->categories as $item) {
        $category = $this->get('mel_app.melblogmanager')->getCategory($item->name);
        $article->addCategory($category);
      }
      // Persistance de l'objet
      $em = $this->getDoctrine()->getManager();
      $em->persist($article);
      $em->flush();
      // Retour du response
      return new JsonResponse([
        'code' => 201,
        'msg' => 'Création de l\'article avec succès'
      ]);
    }

    public function addArticleAction(Request $request) {
      $payload = json_decode($request->getContent());
      try {
        $this->get('mel_app.melblogmanager')->addArticle(
          $this->get('mel_app.melblogfactory')->createArticle($payload)
        );
        
        return new JsonResponse([
          'code' => 201,
          'msg' => 'Création de l\'article avec succès'
        ]);
      } catch (CategorieNotFoundException $e) {
        return new JsonResponse([
          'code' => $e->getCode(),
          // 'code' => Request::CODE_NOT_FOUND,
          'msg' => $e->getMessage()
        ]);
      } catch (CategorieNotDefinedException $e) {
        return new JsonResponse([
          'code' => $e->getCode(),
          'msg' => $e->getMessage()
        ]);
      }
    }

    public function removeArticleAction($articleId) {
      //
    }

    public function editArticleAction($articleId) {
      // 
    }
}
