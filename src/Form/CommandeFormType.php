<?php

namespace App\Form;

use App\Entity\Commande;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
class CommandeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('adresseDep',TextType::class,[
            'label'=>"First Adress",
            'attr' => ['class' => 'form-control']
        ])
        ->add('adresseArr',TextType::class,[
            'label'=>"Second Adress",
'attr' => ['class' => 'form-control']            
        ])
        ->add('typeLivraison', ChoiceType::class, [
            'choices'  => [
                'Standard' => 'standard',
                'Express' => 'express',
            ],
            'label' => 'Delivery Type',
'attr' => ['class' => 'form-control']
        ])
        ->add('horaire', DateTimeType::class, [
            'widget' => 'single_text',
            'disabled' => true,
            'label' => 'Order Time',
'attr' => ['class' => 'form-control']
        ])
        ->add('statut', ChoiceType::class, [
            'choices' => [
                'Shipping' => 'shipping',
                'Processing' => 'processing',
                'Delivered' => 'delivered'
            ],
            'label' => 'Status',
            'required' => true,
            'data' => $builder->getData()->getStatut(), // Add this line to bind current value
            'empty_data' => null,
            'attr' => ['class' => 'form-control']
        ])
        ->add('createdByDisplay', TextType::class, [
            'mapped' => false,
            'data' => $options['username'],
            'disabled' => true,
            'label' => 'Created By',
'attr' => ['class' => 'form-control']
        ]);
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
            'username' => '',
        ]);
    }
}
