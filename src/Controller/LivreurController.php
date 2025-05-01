<?php

namespace App\Controller;
use App\Entity\Commande;
use App\Entity\Facture;
use App\Entity\User;
use App\Service\MailerService;
use App\Entity\Livraison;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\QrCodeService;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
        $commandes = $em
            ->getRepository(Commande::class)
            ->findBy(['statut' =>'Processing']);

        return $this->render('livreur/available.html.twig', [
            'commandes' => $commandes
        ]);
    }

    #[Route('/accept-delivery/{id}', name: 'app_livreur_accept')]
    public function acceptDelivery(QrCodeService $qrCodeService, EntityManagerInterface $em, Request $request, MailerService $mailerService,): Response
    {

        $livreur=$em
        ->getRepository(User::class)
        ->find('65');

        $command = $em->getRepository(Commande::class)->find($request->get('id'));
        $createdBy=$command->getCreatedBy();

        $facture=$em
        ->getRepository(Facture::class)
        ->findOneBy(['commandeId' =>$request->get('id')]);

        $livraison = new Livraison();
        $livraison->setCreatedAt(new \DateTime());
        $livraison->setCreatedBy($createdBy);
        $livraison->setCommandeId($command);
        $livraison->setFactureId($facture);
        $livraison->setZoneId('12');
        $livraison->setIdLivreur($livreur);
        $command->setStatut('Shipping');

        $qrCodeUrl = $this->generateUrl('app_livreur_complete', 
        ['id' => $command->getIdCommande()], UrlGeneratorInterface::ABSOLUTE_URL);
        $qrCodeImage = $qrCodeService->generateQrCode($qrCodeUrl); // Générer le QR code

        $clientEmail=$createdBy->getEmail();
        $subject = "Votre commande est en cours de livraison";
        $content = "Votre commande est en train d'être livrée. Voici votre QR code pour confirmer la réception lors de la livraison :";
    
        $mailerService->sendQrEmail(
            $clientEmail,
            $subject,
            $content,
            $qrCodeImage
        );
        
        $em->persist($livraison);
        $em->persist($command);
        $em->flush();

        return $this->redirectToRoute('app_livreur_current');
    }

    #[Route('/complete-delivery/{id}', name: 'app_livreur_complete')]
    public function completeDelivery( EntityManagerInterface $em, Request $request): Response
    {
        $command = $em->getRepository(Commande::class)->find($request->get('id'));
        $command->setStatut('Delivered');
        
        $em->persist($command);
        $em->flush();

        return $this->redirectToRoute('app_livreur_history');
    }

    #[Route('/my-history', name: 'app_livreur_history')]
    public function myHistory(EntityManagerInterface $em): Response
    {
        $livreurId = 65;

        $qb = $em->createQueryBuilder();

        $qb->select('c')
            ->from(Commande::class, 'c')
            ->join('c.livraisons', 'l')
            ->where('l.id_livreur = :livreurId')
            ->andWhere('c.statut = :statut')
            ->setParameter('livreurId', $livreurId)
            ->setParameter('statut', 'delivered');

        $commandes = $qb->getQuery()->getResult();

        return $this->render('livreur/history.html.twig', [
            'deliveries' => $commandes
        ]);
    }

    #[Route('/my-deliveries', name: 'app_livreur_current')]
public function myDeliveries(EntityManagerInterface $em): Response
{
    $livreurId = 65;

    $qb = $em->createQueryBuilder();

    $qb->select('c')
        ->from(Commande::class, 'c')
        ->join('c.livraisons', 'l')
        ->where('l.id_livreur = :livreurId')
        ->andWhere('c.statut = :statut')
        ->setParameter('livreurId', $livreurId)
        ->setParameter('statut', 'shipping');

    $commandes = $qb->getQuery()->getResult();

    return $this->render('livreur/current.html.twig', [
        'deliveries' => $commandes
    ]);
}

#[Route('/qr-code', name: 'app_qr_code')]
    public function show(QrCodeService $qrCodeService): Response
    {
        $qrCode = $qrCodeService->generateQrCode('https://google.com');

        return $this->render('livreur/qrCode.html.twig', [
            'qrCodeImage' => $qrCode,
        ]);
    }

}

