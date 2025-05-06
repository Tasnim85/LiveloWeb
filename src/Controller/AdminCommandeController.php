<?php

namespace App\Controller;

use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\CommandeFormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class AdminCommandeController extends AbstractController
{
    #[Route('/admin/commande', name: 'app_admin_commande')]
    public function index(Request $request ,EntityManagerInterface $entityManager,FormFactoryInterface $formFactory): Response
    {

       
                $commandes=$entityManager
                ->getRepository(Commande::class)
                ->findAll();
            
            $forms = [];  

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
        
        #[Route('/admin/commande/search', name: 'app_admin_commande_search')]
public function search(Request $request, EntityManagerInterface $entityManager): JsonResponse
{
    $searchValue = strtolower($request->query->get('searchValue', ''));
    
    $repository = $entityManager->getRepository(Commande::class);
    
    $query = $repository->createQueryBuilder('c')
        ->leftJoin('c.createdBy', 'u')
        ->where('LOWER(c.idCommande) LIKE :searchValue')
        ->orWhere('LOWER(u.nom) LIKE :searchValue')
        ->orWhere('LOWER(u.prenom) LIKE :searchValue')
        ->orWhere('LOWER(c.statut) LIKE :searchValue')
        ->orWhere('LOWER(c.adresseDep) LIKE :searchValue')  // Added address fields
        ->orWhere('LOWER(c.adresseArr) LIKE :searchValue')  // Added address fields
        ->setParameter('searchValue', '%'.$searchValue.'%');

    $commandes = $query->getQuery()->getResult();

    $data = [];
    foreach ($commandes as $commande) {
        $data[] = [
            'idCommande' => $commande->getIdCommande(),
            'adresseDep' => $commande->getAdresseDep(),
            'adresseArr' => $commande->getAdresseArr(),
            'typeLivraison' => $commande->getTypeLivraison(),
            'horaire' => $commande->getHoraire() ? $commande->getHoraire()->format('Y-m-d H:i:s') : null,
            'statut' => $commande->getStatut(),
            'createdBy' => $commande->getCreatedBy() ? [
                'nom' => $commande->getCreatedBy()->getNom(),
                'prenom' => $commande->getCreatedBy()->getPrenom()
            ] : null
        ];
    }

    return new JsonResponse($data);
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
