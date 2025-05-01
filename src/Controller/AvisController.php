<?php

namespace App\Controller;

use App\Entity\Avis;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\User;
use App\Entity\Livraison;
use App\Form\AvisType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\LivraisonRepository;


#[Route('/avis')]
final class AvisController extends AbstractController{
    #[Route(name: 'app_avis_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $avis = $entityManager
            ->getRepository(Avis::class)
            ->findAll();

        return $this->render('avis/index.html.twig', [
            'avis' => $avis,
        ]);
    }

    #[Route(path:'/deliveries', name: 'app_list_deliveries', methods: ['GET', 'POST'])]
    public function front(Request $request, EntityManagerInterface $entityManager, LivraisonRepository $livraisonRepository,): Response
    {
        $livraisons = $livraisonRepository->findByCreatedBy('64');
        return $this->render('avis/listDeliveries.html.twig', [
            'livraisons' => $livraisons,           
        ]);
    }

    
#[Route('/avis/new', name: 'app_avis_new', methods: ['POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $description = $request->request->get('description');

    if (!$description || strlen($description) < 3) {
        return new JsonResponse(['error' => 'Invalid description'], 400);
    }

    // Example: Replace with actual logic to get correct Livraison and User
    $livraison = $entityManager->getRepository(Livraison::class)->find(42);
    $createdBy = $entityManager->getRepository(User::class)->find(63);

    if (!$livraison || !$createdBy) {
        return new JsonResponse(['error' => 'Invalid data'], 400);
    }

    $avis = new Avis();
    $avis->setCreatedAt(new \DateTime());
    $avis->setLivraisonId($livraison);
    $avis->setDescription($description);
    $avis->setCreatedBy($createdBy);

    $entityManager->persist($avis);
    $entityManager->flush();

    return new JsonResponse(['message' => 'Avis submitted successfully!'], 200);
}

    // #[Route(name: 'app_avis_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager, LivraisonRepository $livraisonRepository): Response
    // {
    //     $avis = new Avis();
    //     $description = $request->request->get('description');

    //     $livraison = $entityManager->getRepository(Livraison::class)->find(42);
    //     $createdBy = $entityManager->getRepository(User::class)->find(63);

    //     $avis->setCreatedAt(new \DateTime());
    //     $avis->setLivraisonId($livraison);
    //     $avis->setDescription($description);
    //     $avis->setCreatedBy($createdBy);

    //     $entityManager->persist($avis);
    //     $entityManager->flush();

    //     return $this->redirectToRoute('app_avis_index', [], Response::HTTP_SEE_OTHER);
    // }

    // #[Route('/new', name: 'app_avis_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager, Avis $avi): Response
    // {
    //     $avi = new Avis();
    //     $form = $this->createForm(AvisType::class, $avi);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $avi->setCreatedAt(new \DateTime());
    //         $entityManager->persist($avi);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_avis_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('avis/new.html.twig', [
    //         'avi' => $avi,
    //         'form' => $form,
    //     ]);
    // }

    #[Route('/{idAvis}', name: 'app_avis_show', methods: ['GET'])]
    public function show(Avis $avi): Response
    {
        return $this->render('avis/show.html.twig', [
            'avi' => $avi,
        ]);
    }

    #[Route('/{idAvis}/edit', name: 'app_avis_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Avis $avi, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AvisType::class, $avi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_avis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('avis/edit.html.twig', [
            'avi' => $avi,
            'form' => $form,
        ]);
    }

    #[Route('/{idAvis}/delete', name: 'app_avis_delete', methods: ['POST'])]
    public function delete(Request $request, Avis $avi, EntityManagerInterface $entityManager): Response
    {
        // if ($this->isCsrfTokenValid('delete'.$avi->getIdAvis(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($avi);
            $entityManager->flush();
        // }

        return $this->redirectToRoute('app_avis_index', [], Response::HTTP_SEE_OTHER);
    }

    // #[Route('/mydeliveries', name: 'app_delivery', methods: ['GET'])]
    // public function delivery(Avis $avi): Response
    // {
    //     return $this->render('avis/show.html.twig', [
    //         'avi' => $avi,
    //     ]);
    // }
}
