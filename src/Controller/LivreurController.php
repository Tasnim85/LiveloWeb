<?php

namespace App\Controller;
use App\Entity\Commande;
use App\Entity\User;
use App\Entity\Livraison;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/livreur')]
class LivreurController extends AbstractController
{
    #[Route('', name: 'app_livreur_index')]
    public function index(): Response
    {
        return $this->redirectToRoute('app_livreur_current');
    }

    #[Route('/available-deliveries', name: 'app_livreur_available')]
    public function availableDeliveries(EntityManagerInterface $em): Response
    {
        $availableDeliveries = $em->getRepository(Livraison::class)
            ->createQueryBuilder('l')
            ->join('l.commandeId', 'c')
            ->where('l.id_livreur IS NULL')
            ->andWhere('c.statut = :status')
            ->setParameter('status', 'Processing')
            ->getQuery()
            ->getResult();

        return $this->render('livreur/available.html.twig', [
            'deliveries' => $availableDeliveries
        ]);
    }

    #[Route('/accept-delivery/{id}', name: 'app_livreur_accept')]
    public function acceptDelivery(Livraison $livraison, EntityManagerInterface $em): Response
    {
        $livraison->setIdLivreur($this->getUser());
        $commande = $livraison->getCommandeId();
        $commande->setStatut('Shipping');
        
        $em->persist($livraison);
        $em->persist($commande);
        $em->flush();

        return $this->redirectToRoute('app_livreur_current');
    }

    #[Route('/complete-delivery/{id}', name: 'app_livreur_complete')]
    public function completeDelivery(Livraison $livraison, EntityManagerInterface $em): Response
    {
        $commande = $livraison->getCommandeId();
        $commande->setStatut('Delivered');
        
        $em->persist($commande);
        $em->flush();

        return $this->redirectToRoute('app_livreur_current');
    }

    #[Route('/my-history', name: 'app_livreur_history')]
    public function myHistory(EntityManagerInterface $em): Response
    {
        $myHistory = $em->getRepository(Livraison::class)
            ->createQueryBuilder('l')
            ->join('l.commandeId', 'c')
            ->where('l.id_livreur = :livreur')
            ->andWhere('c.statut = :status')
            ->setParameter('livreur', $this->getUser())
            ->setParameter('status', 'Delivered')
            ->orderBy('l.created_at', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('livreur/history.html.twig', [
            'deliveries' => $myHistory
        ]);
    }

    #[Route('/my-deliveries', name: 'app_livreur_current')]
    public function myDeliveries(EntityManagerInterface $em): Response
    {
        $currentDeliveries = $em->getRepository(Livraison::class)
            ->createQueryBuilder('l')
            ->join('l.commandeId', 'c')
            ->where('l.id_livreur = :livreur')
            ->andWhere('c.statut = :status')
            ->setParameter('livreur', $this->getUser())
            ->setParameter('status', 'Shipping')
            ->getQuery()
            ->getResult();

        return $this->render('livreur/current.html.twig', [
            'deliveries' => $currentDeliveries
        ]);
    }
}

