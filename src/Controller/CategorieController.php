<?php

namespace App\Controller;
use App\Entity\User;
use App\Repository\ArticleRepository;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;


#[Route('/categorie')]
final class CategorieController extends AbstractController
{
    #[Route('/', name: 'app_categorie_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager
            ->getRepository(Categorie::class)
            ->findAll();
    
        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,
        ]);
    }
  
    

    #[Route('/categorie/new', name: 'app_categorie_new')]
    public function new(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        if ($request->isMethod('POST')) {
            $categorie = new Categorie();
    
            $categorie->setNom($request->request->get('nom'));
            $categorie->setDescription($request->request->get('description'));
    
            // Gestion de l'image
            $imageFile = $request->files->get('url_image');
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
    
                // Spécifier directement le dossier où sauvegarder l'image
                $destination = $this->getParameter('kernel.project_dir') . '/public/img';
                $imageFile->move(
                    $destination,
                    $newFilename
                );
    
                $categorie->setUrl_image('/img/' . $newFilename);
            }
    
            // Ajout manuel d'un utilisateur temporaire pour le champ created_by
            $user = $em->getRepository(User::class)->find(62); // ID temporaire
            $categorie->setCreated_by($user);
    
            $em->persist($categorie);
            $em->flush();
    
            // Rediriger vers la liste des catégories
            return $this->redirectToRoute('app_categorie_index');
        }
    
        return $this->render('categorie/index.html.twig', [
            'categorie' => new Categorie(), // éviter variable undefined
        ]);
    }
    
    
    

    #[Route('/categorie/{id_categorie}/edit', name: 'app_categorie_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Categorie $categorie, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(CategorieType::class, $categorie);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();
        return $this->redirectToRoute('app_categorie_index');
    }

    $categories = $entityManager->getRepository(Categorie::class)->findAll();

    return $this->render('categorie/index.html.twig', [
        'categories' => $categories,
        'categorieToEdit' => $categorie,
        'formEdit' => $form->createView(),
    ]);
}

    
    
    
    


    #[Route('/{id_categorie}', name: 'app_categorie_delete', methods: ['POST'])]
    public function delete(Request $request, Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorie->getIdCategorie(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($categorie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
    }
}
