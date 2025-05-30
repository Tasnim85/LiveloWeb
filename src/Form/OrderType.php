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

class OrderType extends AbstractType
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
        ->add('paymentMethod', ChoiceType::class, [
            'choices' => [
                'Cash' => 'cash',
                'Online' => 'online'
            ],
            'expanded' => true,
            'mapped' => false, // This is the key change
            'required' => true
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Null,
        ]);
    }
}
