<?php

namespace App\Controller;

use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminCommandeController extends AbstractController
{
    #[Route('/Admincommande', name: 'app_admin_commande')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $commandes = $entityManager
            ->getRepository(Commande::class)
            ->findAll();

        return $this->render('admin_commande/affichageCommandes.html.twig', [
            'commandes' => $commandes,
        ]);
    }
    #[Route('delete/{id_commande}', name: 'app_commande_delete')]
    public function delete(EntityManagerInterface $entityManager, int $id_commande): Response
    {
        $commande = $entityManager->getRepository(Commande::class)->find($id_commande);
    
        if (!$commande) {
            throw $this->createNotFoundException('Order not found');
        }
    
        $entityManager->remove($commande);
        $entityManager->flush();
    
        $this->addFlash('success', 'Order deleted successfully.');
    
        return $this->redirectToRoute('app_admin_commande'); // Adjust to your route
    }

}
