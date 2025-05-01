<?php

namespace App\Controller;


use App\Entity\User;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use App\Service\ArticleService;
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
    #[Route('/catPartner', name: 'app_cat_partner')]
    public function catPartner(ArticleService $alertService, EntityManagerInterface $entityManager): Response
    {
        // Récupère toutes les catégories
        $categories = $entityManager
            ->getRepository(Categorie::class)
            ->findAll();
    
        // Récupère l'utilisateur par son ID (ici l'ID 66 pour le test)
        $user = $entityManager->getRepository(User::class)->find(66);
    
        // Vérifie si l'utilisateur existe
        if (!$user) {
            // Si l'utilisateur n'existe pas, gérer l'erreur (redirection ou message d'erreur)
            return $this->render('error.html.twig', [
                'message' => 'Utilisateur non trouvé.',
            ]);
        }
    
        // Vérifie les alertes de stock
        $alerts = $alertService->checkLowStock($user);
    
        return $this->render('categorie/catPartner.html.twig', [
            'categories' => $categories,
            'hasAlerts' => !empty($alerts),
            'alertsCount' => count($alerts),
        ]);
    }
    #[Route('/api/alerts', name: 'partner_api_alerts')]
public function getStockAlerts(ArticleService $alertService, EntityManagerInterface $entityManager): Response
{
    // Récupérer l'utilisateur
    $user = $entityManager->getRepository(User::class)->find(66);
    $alerts = $alertService->checkLowStock($user);

    // Retourner la réponse JSON
    return $this->json([
        'hasAlerts' => !empty($alerts),
        'count' => count($alerts),
        'articles' => array_map(fn($article) => $article->getNom(), array_slice($alerts, 0, 3)),
    ]);
}





#[Route('/', name: 'app_categorie_index', methods: ['GET'])]
public function index(EntityManagerInterface $entityManager, CategorieRepository $categorieRepository): Response
{
    // Récupérer toutes les catégories
    $categories = $entityManager->getRepository(Categorie::class)->findAll();

    // Initialiser les variables pour la catégorie avec le plus d'articles
    $maxArticles = 0;
    $famousCategory = null;

    // Parcours des catégories pour trouver celle avec le plus d'articles
    foreach ($categories as $categorie) {
        $articleCount = $categorie->getArticles()->count(); // Vérifie que getArticles() fonctionne correctement
        if ($articleCount > $maxArticles) {
            $maxArticles = $articleCount;
            $famousCategory = $categorie;
        }
    }

    // Passe les variables nécessaires à Twig
    return $this->render('categorie/index.html.twig', [
        'categories' => $categories,           // liste des catégories
        'famousCategory' => $famousCategory,    // la plus célèbre catégorie
        'articleCount' => $maxArticles         // le nombre d'articles de la catégorie
    ]);
}

  
    
    #[Route('/new', name: 'app_categorie_new')]
    public function new(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
    
        // Affiche un message si le formulaire est soumis ou non
        if ($request->isMethod('POST')) {
            dump("Le formulaire catégorie a été soumis !");
        }
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                dump("Le formulaire catégorie n'est pas valide !");
                foreach ($form->getErrors(true) as $error) {
                    dump($error->getMessage());
                }
            } else {
                dump("Le formulaire catégorie est valide, on persiste !");
    
                // Gestion de l'image
                $imageFile = $form->get('url_image')->getData();
                if ($imageFile) {
                    $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
    
                    try {
                        $destination = $this->getParameter('kernel.project_dir').'/public/img';
                        $imageFile->move($destination, $newFilename);
                        $categorie->setUrl_image('/img/'.$newFilename);
                    } catch (FileException $e) {
                        $this->addFlash('error', 'Erreur lors du téléchargement de l\'image');
                        return $this->redirectToRoute('app_categorie_new');
                    }
                }
    
                // Ajout de l'utilisateur
                $user = $em->getRepository(User::class)->find(62); // À remplacer par l’utilisateur connecté
                $categorie->setCreated_by($user);
    
                $em->persist($categorie);
                $em->flush();
    
                $this->addFlash('success', 'Catégorie ajoutée avec succès !');
                return $this->redirectToRoute('app_categorie_index');
            }
        }
    
        return $this->render('categorie/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    
    

    #[Route('/categorie/{id_categorie}/edit', name: 'app_categorie_edit')]
    public function edit(
        Request $request,
        CategorieRepository $categorieRepository,
        EntityManagerInterface $em,
        int $id_categorie
    ): Response {
        $categorie = $categorieRepository->find($id_categorie);
        
        if (!$categorie) {
            throw $this->createNotFoundException('Catégorie introuvable.');
        }
    
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'image si elle a été modifiée
            $imageFile = $form->get('url_image')->getData();
    
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
    
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'), // à configurer dans services.yaml
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Erreur lors de l\'upload de l\'image.');
                }
    
                $categorie->setUrlImage($newFilename);
            }
    
            $em->flush();
    
            $this->addFlash('success', 'Catégorie mise à jour avec succès !');
            return $this->redirectToRoute('app_categorie_index');
        }
    
        return $this->render('categorie/edit.html.twig', [
            'form' => $form->createView(),
            'categorie' => $categorie,
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
