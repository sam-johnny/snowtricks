<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Post;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'label' => 'Titre'
            ])
            ->add('content', null, [
                'label' => 'Contenu'
            ])
            ->add('imageFiles',FileType::class,[
                'required' => false
            ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'label' => 'Catégorie',
                'choice_label' => 'name'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
