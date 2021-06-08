<?php

namespace Sdz\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArticleType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('date', 'date', [
                'mapped' => false,
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control datepicker',
                    'date_format' => "dd-mm-yyyy"
                ]
            ])
            ->add('heure', 'time', [
                'mapped' => false,
                'input' => 'datetime',
                'widget' => 'choice',
                'attr' => [
                    'class' => 'form-control timepicker',
                    'date_format' => "HH:ii"
                ]
            ])
            ->add('title', 'text')
            // ->add('content', 'textarea')
            // Test de ckeditor
            ->add('content', 'ckeditor')
            ->add('author', 'text')
            // ->add('published', 'checkbox', ['required' => false])
            ->add('image', new ImageType())
            // ->add('categories', 'collection', [
            //     'type' => new CategorieType,
            //     'allow_add' => true,
            //     'allow_delete' => true,
            //     'by_reference' => false
            // ])
            ->add('categories', 'entity', [
                'class' => 'SdzBlogBundle:Categorie',
                'property' => 'name',
                'multiple' => true,
                'expanded' => false
            ])
            ->add('commentaires', 'collection', [
                'type' => new CommentaireType,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ])
        ;

        $factory = $builder->getFormFactory();

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function(FormEvent $event) use ($factory) {
                $article = $event->getData();
                if (null === $article) {
                    return;
                }
                // Si l'article n'est pas encore publié, on ajoute le champ published
                if (false === $article->getPublished()) {
                    $event->getForm()->add(
                        $factory->createNamed('published', 'checkbox', null, ['required' => false, 'auto_initialize' => false])
                    );
                } else {
                    $event->getForm()->remove('published');
                }
            }
        );

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function(FormEvent $event) use ($factory) {
                $article = $event->getData();
                $form = $event->getForm();
                $dataForm = $form->getData();

                // dump($dataForm);
                // dump($article);

                // $date = date('Y-m-d H:i:s',strtotime('+'.$article['heure']['hour'].' hour +'.$article['heure']['minute'].' minutes',strtotime($article['date'])));
                // $article['date'] = $date;

                $date = new \Datetime($article['date'].' '.$article['heure']['hour'].':'.$article['heure']['minute']);

                $dataForm->setDate($date);
            }
        );
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Sdz\BlogBundle\Entity\Article',
            // 'cascade_validation' => true
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'sdz_blogbundle_article';
    }
}
