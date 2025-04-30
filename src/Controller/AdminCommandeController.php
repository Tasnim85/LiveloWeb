<?php

namespace App\Controller;

use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\CommandeFormType;
use Symfony\Component\Form\FormFactoryInterface;

final class AdminCommandeController extends AbstractController
{
    #[Route('/admin/commande', name: 'app_admin_commande')]
    public function index(Request $request ,EntityManagerInterface $entityManager,FormFactoryInterface $formFactory): Response
    {
        $commandes = $entityManager
            ->getRepository(Commande::class)
            ->findAll();
            $forms = [];  // Initialisation du tableau des formulaires

            foreach ($commandes as $commande) {
                $form = $formFactory->createNamed(
                    'form_commande_' . $commande->getIdCommande(),
                    CommandeFormType::class,
                    $commande,
                    [
                        'username' => $commande->getCreatedBy()->getNom() . ' ' . $commande->getCreatedBy()->getPrenom(),
                    ]
                );
            
                $form->handleRequest($request);
            
                // Only handle the specific submitted form
                if ($form->isSubmitted()) {
                    if ($form->isValid()) {
                        $entityManager->flush();
                        $this->addFlash('success', 'Order updated successfully.');
                       return $this->redirectToRoute('app_admin_commande');
                    } else {
                        $this->addFlash('error', 'There were validation errors. Please correct them.');
                    }
                }
            
                $forms[$commande->getIdCommande()] = $form->createView();
            }
            
            
            // Passer les données à la vue pour les afficher
            return $this->render('admin_commande/affichageCommandes.html.twig', [
                'commandes' => $commandes,
                'forms' => $forms,
            ]);
            
    }
    #[Route('/Admincommande/delete/{id_commande}', name: 'app_commande_delete')]
    public function delete(EntityManagerInterface $entityManager, int $id_commande): Response
    {
        $commande = $entityManager->getRepository(Commande::class)->find($id_commande);
    
        if (!$commande) {
            throw $this->createNotFoundException('Order not found');
        }
    
        $entityManager->remove($commande);
        $entityManager->flush();
    
        $this->addFlash('success', 'Order deleted successfully.');
    
        return $this->redirectToRoute('app_admin_commande'); 
    }
    /*#[Route('/Admincommande/edit/{id_commande}', name: 'app_commande_edit')]
        public function edit(Request $request, EntityManagerInterface $entityManager, int $id_commande): Response
        {
            $commande = $entityManager->getRepository(Commande::class)->find($id_commande);
             if (!$commande) {
            throw $this->createNotFoundException('Order not found');
            }
            
            $form = $this->createForm(CommandeFormType::class, $commande, [
                'username' => $commande->getCreatedBy()->getNom() . ' ' . $commande->getCreatedBy()->getPrenom(),
            ]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Order updated successfully.');

        return $this->redirectToRoute('app_admin_commande');
        }

             return $this->render('admin_commande/.html.twig', [
            'form' => $form->createView(),
        ]);
    }*/
    
}
