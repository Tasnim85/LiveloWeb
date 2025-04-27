<?php

namespace App\Form;

use App\Entity\Categorie;
<<<<<<< HEAD

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

=======
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\NotBlank;
>>>>>>> master

class CategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la catégorie',
<<<<<<< HEAD

                'attr' => [
                    'placeholder' => 'Ex: Mode, Électronique...',
                    'class' => 'form-control'
                ],
                'required' => true
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Une brève description...',
                    'rows' => 4,
                    'class' => 'form-control'
                ],
                'required' => true
            ])
            ->add('url_image', FileType::class, [
                'label' => 'Image (JPEG, PNG, WebP)',
                'mapped' => false, // car ce champ ne correspond pas directement à une propriété de l'entité
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp'],
                        'mimeTypesMessage' => 'Formats acceptés : JPEG, PNG, WebP',
                    ])
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
        ;

=======
                'constraints' => [
                    new NotBlank(['message' => 'Le nom de la catégorie est requis.'])
                ]
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
                'constraints' => [
                    new NotBlank(['message' => 'La description est requise.'])
                ]
            ])
            ->add('url_image', FileType::class, [
                'label' => 'Image descriptive',
                'mapped' => false, // Important pour le fichier
                'required' => false,
            ])
            ;
>>>>>>> master
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}

