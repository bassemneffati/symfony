<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $builder
            ->add('id')
            ->add('title')
            ->add('category', ChoiceType::class,
            ['choices' => ['mystery'=>'mystery',
           ' Science-Fiction'=>'Science-Fiction',
           'Autobiography'=>'Autobiography']])
            ->add('published')
            ->add('publicationDate')
            ->add('author',EntityType::class, ['class'=>Author::class,
            'choice_label'=>'username',
            'multiple'=>false,
            'expanded'=>false,
            'required'=>true,
            'placeholder'=>"Veuillez choisir un auteur"])
            ->add('save',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
    
}
