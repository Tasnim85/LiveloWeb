<?php

namespace App\Form;

use App\Entity\Commande;
use App\Entity\Facture;
use App\Entity\Livraison;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivraisonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('created_at', DateType::class, [
            //     'widget' => 'single_text',
            // ])
            ->add('created_by', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'getIdUser',
                'label' => 'Created By'
            ])
            ->add('id_livreur', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'getIdUser',
                'label' => 'Delivery Person'
            ])
            ->add('commandeId', EntityType::class, [
                'class' => Commande::class,
                'choice_label' => 'id_commande',
                'label' => 'Order'
            ])
            ->add('factureId', EntityType::class, [
                'class' => Facture::class,
                'choice_label' => 'idFacture',
                'label' => 'Invoice'
            ])
            ->add('zoneId', IntegerType::class, [
                'label' => 'Zone ID'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livraison::class,
        ]);
    }
}









