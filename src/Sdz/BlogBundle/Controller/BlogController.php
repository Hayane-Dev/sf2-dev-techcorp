<?php

namespace Sdz\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sdz\BlogBundle\Entity\Article;
use Sdz\BlogBundle\Entity\ArticleCompetence;
use Sdz\BlogBundle\Entity\Categorie;
use Sdz\BlogBundle\Entity\Commentaire;
use Sdz\BlogBundle\Entity\Competence;
use Sdz\BlogBundle\Entity\Image;

class BlogController extends Controller
{
    public function indexAction($page)
    {
        if ($page < 1) {
            throw $this->createNotFoundException('Page inexistante (page = '.$page.')');
        } 

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('SdzBlogBundle:Article');
        $articles = $repository->findAll();
        
        return $this->render('SdzBlogBundle:Blog:index.html.twig', ['articles' => $articles]);
    }

    public function menuAction($number) 
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('SdzBlogBundle:Article');
        $lastArticlesList = $repo->findBy(
            [],
            ['date' => 'DESC'],
            $number,
            0
        );

        return $this->render('blog/menu.html.twig', ['list'=> $lastArticlesList]);
    }

    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryArticle = $em->getRepository('SdzBlogBundle:Article');

        $article = $repositoryArticle->find($id);
        // dump($article->getImage()->getUrl()); LAZY LOADING

        if (null === $article) {
            throw $this->createNotFoundException('Article[id='.$id.'] inexistant');
        }

        // On récupère les commentaires associés à l'article : tant que la relation était unidirectionnelle, on avait besoin de faire ça !!! La relation étant bidirectionnelle les commentaires sont maintenant accessible vie $article->getCommentaires(), au niveau du Twig article.commentaires
        // $repositoryCommentaire = $em->getRepository('SdzBlogBundle:Commentaire');
        // $commentaires = $repositoryCommentaire->findByArticle($article->getId());

        // On récupère les compétences liées à l'article
        $article_competences = $em->getRepository('SdzBlogBundle:ArticleCompetence')->findByArticle($article->getId());
        

        return $this->render('SdzBlogBundle:Blog:view.html.twig', [
            'article' => $article, 
            // 'commentaires' => $commentaires,
            'article_competences' => $article_competences
        ]);
    }

    public function addAction()
    {
        // Ajouter un article...
        $article = new Article();
        $article->setTitle('Git')
                ->setAuthor('Mek')
                ->setContent('Git is a free and open source distributed version control system designed to handle everything from small to very large projects with speed and efficiency');

        // Lier une image à un article...
        $image = new Image();
        $image->setUrl('img/photo.png')
              ->setAlt('image');

        $article->setImage($image);

        // Avant que la relation ne devienne bidirectionnelle
        // Lier des commentaires à un article
        $comment1 = new Commentaire();
        $comment1->setAuthor('IbnMek')
                 ->setContent('https://git-scm.com/')
                 ->setArticle($article); 
        $comment2 = new Commentaire();
        $comment2->setAuthor('IbnAbass')
                 ->setContent('https://fr.wikipedia.org/wiki/Git')
                 ->setArticle($article); 

        // Relation bidirectionnelle
        $article->addCommentaire($comment1);
        $article->addCommentaire($comment2);


        $em = $this->getDoctrine()->getManager();

        // Lier les catégories
        $categories = $em->getRepository('SdzBlogBundle:Categorie')->findAll();
        foreach ($categories as $categorie) {
            $article->addCategory($categorie);
        }

        $em->persist($article);
        // Persist en cascade pour eviter ces 2 lignes
        $em->persist($comment1);
        $em->persist($comment2);

        // Les compétences
        // Récup depuis la DB
        $competences = $em->getRepository('SdzBlogBundle:Competence')->findAll();
        foreach ($competences as $key => $competence) {
            $articleCompetence[$key] = new ArticleCompetence();
            $articleCompetence[$key]->setArticle($article)
                                    ->setCompetence($competence)
                                    ->setLevel('Intermédiaire');
            $em->persist($articleCompetence[$key]);
        }

        $em->flush();

        if ($this->get('request')->getMethod() == 'POST') {
            // Traitement du formulaire, persister les datas en base
            // Message flash
            // $this->get('session')->getFlashBag()->add('notice', 'Article bien enregistré');
            $this->get('session')->getFlashBag()->add('info', 'Article bien enregistré');
            // Redirection vers la page de visualisation de l'article
            return $this->redirect($this->generateUrl('sdz_blog_view', ['id' => $article->getId()]));
        }

        // Affichage du formulaire d'ajout d'article
        return $this->render('SdzBlogBundle:Blog:add.html.twig');
    }

    public function editAction($id)
    {
        // Récupération de l'article d'id = $id
        $em = $this->getDoctrine()->getManager();
        $repositoryArticle = $em->getRepository('SdzBlogBundle:Article');

        $article = $repositoryArticle->find($id);

        if (null === $article) {
            throw $this->createNotFoundException('Article[id='.$id.'] inexistant');
        }

        // Création et gestion du formulaire
        if ($this->get('request')->getMethod() == 'POST') {
            // Traitement du formulaire, persister les datas en base
            // Message flash
            $this->get('session')->getFlashBag()->add('info', 'Article bien modifié');
            // Redirection vers la page de visualisation de l'article
            return $this->redirect($this->generateUrl('sdz_blog_view', ['id' => $article->getId()]));
        }

        return $this->render('SdzBlogBundle:Blog:edit.html.twig', ['article' => $article]);
    }

    public function removeAction($id) 
    {
        // Récupération de l'article d'id = $id
        $em = $this->getDoctrine()->getManager();
        $repositoryArticle = $em->getRepository('SdzBlogBundle:Article');

        $article = $repositoryArticle->find($id);

        if (null === $article) {
            throw $this->createNotFoundException('Article[id='.$id.'] inexistant');
        }

        if ($this->get('request')->getMethod() == 'POST') {
            // Traitement du formulaire, persister les datas en base
            // Message flash
            $this->get('session')->getFlashBag()->add('info', 'Article bien supprimé');
            // Redirection vers la page d'accueil
            return $this->redirect($this->generateUrl('sdz_blog_home'));
        }

        // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
        return $this->render('SdzBlogBundle:Blog:remove.html.twig', ['article' => $article]);
    }
}

