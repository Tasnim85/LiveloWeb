<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CommandeController extends AbstractController
{
    // #[Route(name: 'app_commande_index', methods: ['GET'])]
    // public function index(EntityManagerInterface $entityManager): Response
    // {
    //     $commandes = $entityManager
    //         ->getRepository(Commande::class)
    //         ->findAll();

    //     return $this->render('commande/index.html.twig', [
    //         'commandes' => $commandes,
    //     ]);
    // }

    // #[Route('/new', name: 'app_commande_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $commande = new Commande();
    //     $form = $this->createForm(CommandeType::class, $commande);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->persist($commande);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('commande/new.html.twig', [
    //         'commande' => $commande,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/{id_commande}', name: 'app_commande_show', methods: ['GET'])]
    // public function show(Commande $commande): Response
    // {
    //     return $this->render('commande/show.html.twig', [
    //         'commande' => $commande,
    //     ]);
    // }

    // #[Route('/{id_commande}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(CommandeType::class, $commande);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('commande/edit.html.twig', [
    //         'commande' => $commande,
    //         'form' => $form,
    //     ]);
    // }

    
}
