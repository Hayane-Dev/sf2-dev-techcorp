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
use Sdz\BlogBundle\Form\ArticleType;

class BlogController extends Controller
{
    // Test
    public function voirSlugAction($year, $slug, $format)
    {
        return new Response("Affichage des paramètres de l'url: année($year), slug($slug), format($format)");
    }

    public function sendMailAction()
    {
        // Récupération du service
        $mailer = $this->get('mailer');
        // Le service mailer utilise SwiftMailer
        $message = \Swift_Message::newInstance()
            ->setSubject("Hello Mek")
            ->setFrom("logimek72@gmail.com")
            ->setTo("logimek72@gmail.com")
            ->setBody("Test envoi de mail dear Mek !!!");
        $mailer->send($message);

        return new Response("Mail bien envoyé.");

        // NB: Configuration des paramètres pour l'envoi de mail
        // app/config/parameters.yml
    }

    public function defaultAction($page)
    {
        if ($page < 1)
        {
            throw $this->createNotFoundException('Page inexistante (page = '.$page.')');
        }

        return new Response('La page est existante !!!');
    }    

    public function indexAction($page)
    {
        // if ($page < 1) {
        //     throw $this->createNotFoundException('Page inexistante (page = '.$page.')');
        // } 
        // Cette partie est déplacée dans le fichier ArticleRepository.php dans la méthode getArticles()

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('SdzBlogBundle:Article');

        // $articles = $repository->findAll();
        $articles = $repository->getArticles(3, $page);
        // Quand on fera un $article->getImage() ou $artilce->getCategories() ou $article->getCommentaires()
        // Aucunes autres requêtes ne sera réalisées...contrairement au findAll()
        // dump($articles);die();
        
        return $this->render('SdzBlogBundle:Blog:index.html.twig', [
            'articles' => $articles,
            'page' => $page,
            'nbTotalPages' => ceil(count($articles)/3) //ceil function: arrondi au nb entier supérieur
        ]);
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

    public function viewAction(Article $article)
    // public function viewAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /*
        // Avec l'utilsation d'un ParamConverter, le code ci-dessous devient useless
        $repositoryArticle = $em->getRepository('SdzBlogBundle:Article');

        $article = $repositoryArticle->find($id);

        if (null === $article) {
            throw $this->createNotFoundException('Article[id='.$id.'] inexistant');
        }
        */

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

    public function add2Action()
    {
        // Ajouter un article...
        $article = new Article();
        $article->setTitle('Symfony et les formulaires ')
                ->setAuthor('Mek')
                ->setContent('Les formulaires Symfony...');

        // Lier une image à un article...
        $image = new Image();
        $image->setUrl('img/photo.png')
              ->setAlt('image');

        $article->setImage($image);

        // Avant que la relation ne devienne bidirectionnelle
        // Lier des commentaires à un article
        $comment1 = new Commentaire();
        $comment1->setAuthor('IbnMek')
                 ->setContent('Le form builder')
                 ->setArticle($article); 
        $comment2 = new Commentaire();
        $comment2->setAuthor('IbnAbass')
                 ->setContent('Imbrication de formulaire')
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

    // Utilisation des formulaires
    public function addAction()
    {
        $article = new Article();
        // Sans le ArticleType
        /*$formBuilder = $this->createFormBuilder($article);
        // On ajoute les champs que l'on veut au formmulaire
        $formBuilder->add('date', 'date')
                    ->add('title', 'text')
                    ->add('content', 'textarea')
                    ->add('author', 'text')
                    ->add('published', 'checkbox', ['required' => false]);
        // On génére le formulaire
        $form = $formBuilder->getForm();*/
        // Avec le ArticleType
        $form = $this->createForm(new ArticleType, $article);

        // Récupération de la requête
        $request = $this->get('request');
        // Vérif requête en POST
        if ($request->getMethod() == 'POST') {
            // dump($request);die();
            // On lie les données de la requête au formulaire
            $form->bind($request);

            // $validator = $this->get('validator');
            // $aErrors = $validator->validate($article);
            // Ici on doit tout gérer nous même, récupération des erreurs et affichage dans le template
            // dump($aErrors);
            // dump($article);
            // die();

            // Vérif de la validité des données automatique sur les formulaires
            // Les erreurs sont assignées au formulaire et affichées dans la vue => nous n'avons rien à faire !
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                // $em->persist($article);
                // $em->flush();
                dump($form->getData());
                die();

                // Redirection vers view article 
                return $this->redirect($this->generateUrl('sdz_blog_view', [
                    'id' => $article->getId()
                ]));
            }
        }

        // On passe à la vue, la méthode createView() du formulaire, afin qu'elle puisse affichier le formulaire 
        return $this->render('SdzBlogBundle:Blog:add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function editAction(Article $article)
    // public function editAction($id)
    {
        // Récupération de l'article d'id = $id
        // $em = $this->getDoctrine()->getManager();
        /*
        // ParamConverter
        $repositoryArticle = $em->getRepository('SdzBlogBundle:Article');

        $article = $repositoryArticle->find($id);

        if (null === $article) {
            throw $this->createNotFoundException('Article[id='.$id.'] inexistant');
        }
        */

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

    public function removeAction(Article $article) 
    // public function removeAction($id) 
    {
        // Récupération de l'article d'id = $id
        // $em = $this->getDoctrine()->getManager();
        /*
        // Utilisation d'un ParamConverter
        $repositoryArticle = $em->getRepository('SdzBlogBundle:Article');

        $article = $repositoryArticle->find($id);

        if (null === $article) {
            throw $this->createNotFoundException('Article[id='.$id.'] inexistant');
        }
        */
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

    /**
     * Test de la validation des données Service Validator
     */
    public function testValidatorAction() 
    {
        // Tester le service sdz_blog.antispam
        $antispam = $this->get("sdz_blog.antispam");
        $text = "slkdfjlkdsjflsdfldsfldsfdslf@sdfdsf.fr||dsfsfs@dsfsdf.der||dmlfdslfmsd@dfsdf.gh";
        // $text = "Moins de trois liens et adresses mails";
        if ($antispam->isSpam($text)) {
            exit("Votre message a été détecté comme spam !");
        }

        if (true) return $this->render("SdzBlogBundle:Blog:test.html.twig");
        // dump($antispam);
        // die("OK");
        
        $article = new Article();
        $article->setDate(new \DateTime());
        $article->setTitle('ABC');
        $article->setAuthor('A');

        $validator = $this->get('validator');
        $aErrors = $validator->validate($article);

        dump($aErrors);
        die();

        // return new Response("Test Validator Component");
    }
}

