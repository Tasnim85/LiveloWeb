<?php

namespace App\Controller;

use App\Entity\Livraison;
use App\Entity\User;
use App\Entity\Avis;
use App\Entity\Commande;
use App\Form\LivraisonType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/livraison')]
final class LivraisonController extends AbstractController{
    #[Route(name: 'app_livraison_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $livraisons = $entityManager
            ->getRepository(Livraison::class)
            ->findAll();

        return $this->render('livraison/index.html.twig', [
            'livraisons' => $livraisons,
        ]);
    }

    #[Route('/new', name: 'app_livraison_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $livraison = new Livraison();
        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $livraison->setCreatedAt(new \DateTime());
            $entityManager->persist($livraison);
            $entityManager->flush();

            return $this->redirectToRoute('app_livraison_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('livraison/new.html.twig', [
            'livraison' => $livraison,
            'form' => $form,
        ]);
    }

    #[Route('/{idLivraison}', name: 'app_livraison_show', methods: ['GET'])]
    public function show(Livraison $livraison, EntityManagerInterface $entityManager): Response
    {
        $commandID=$livraison->getCommandeId();
        $command = $entityManager->getRepository(Commande::class)->find($commandID);

        $clientID=$livraison->getCreatedBy();
        $client=$entityManager->getRepository(User::class)->find($clientID);

        $livreurID=$livraison->getIdLivreur();
        $livreur=$entityManager->getRepository(User::class)->find($livreurID);

        $avis = $entityManager->getRepository(Avis::class)->findOneBy([
            'livraisonId' => $livraison
        ]);        
        
        return $this->render('livraison/show.html.twig', [
            'livraison' => $livraison,
            'command'=>$command,
            'client'=>$client,
            'livreur'=>$livreur,
            'avis'=>$avis
        ]);
    }

    #[Route('/{idLivraison}/edit', name: 'app_livraison_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Livraison $livraison, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_livraison_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('livraison/edit.html.twig', [
            'livraison' => $livraison,
            'form' => $form,
        ]);
    }

    #[Route('/{idLivraison}/delete', name: 'app_livraison_delete', methods: ['POST'])]
    public function delete(Livraison $livraison, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($livraison);
        $entityManager->flush();

        return $this->redirectToRoute('app_livraison_index', [], Response::HTTP_SEE_OTHER);
    }


}
