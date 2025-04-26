<?php

namespace App\Controller;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use App\Entity\Article;
// Ajoutez cette ligne avec les autres use statements
use App\Entity\User;
// Dans ArticleController.php
use App\Entity\Categorie;  // Ajoutez cette ligne
// Vérifiez que celle-ci existe aussi
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
#[Route('/article')]
final class ArticleController extends AbstractController
{
    #[Route('/articles', name: 'app_article_index', methods: ['GET'])]
public function index(EntityManagerInterface $entityManager): Response
{
    $articles = $entityManager->getRepository(Article::class)->findAll();

    // Grouper les articles par catégorie
    $articlesParCategorie = [];
    foreach ($articles as $article) {
        $categorie = $article->getId_categorie();
        $id = $categorie->getIdCategorie();
        if (!isset($articlesParCategorie[$id])) {
            $articlesParCategorie[$id] = [
                'categorie' => $categorie,
                'articles' => [],
            ];
        }
        $articlesParCategorie[$id]['articles'][] = $article;
    }

    return $this->render('article/index.html.twig', [
        'articlesParCategorie' => $articlesParCategorie,
    ]);
}

#[Route('/article/categorie/{id_categorie}', name: 'articles_par_categorie')]
public function articlesByCategorie(
    int $id_categorie,
    ArticleRepository $articleRepository,
    CategorieRepository $categorieRepository
): Response {
    $articles = $articleRepository->findByCategorie($id_categorie);
    $categorie = $categorieRepository->find($id_categorie);

    return $this->render('article/index.html.twig', [
        'articles' => $articles,
        'categorie' => $categorie,
    ]);
}





#[Route('/article/new', name: 'app_article_new', methods: ['GET', 'POST'])]
public function new(
    Request $request,
    EntityManagerInterface $em,
    SluggerInterface $slugger,
    int $id_categorie
): Response {
    // Vérification de l'existence de la catégorie
    $categorie = $em->getRepository(Categorie::class)->find($id_categorie);
    if (!$categorie) {
        throw $this->createNotFoundException('Catégorie non trouvée');
    }

    if ($request->isMethod('POST')) {
        $article = new Article();
       // $article->setIdCategorie($categorie);
    
        // Données obligatoires
        $article->setNom($request->request->get('nom'));
        $article->setDescription($request->request->get('description'));
        $article->setPrix((float)$request->request->get('prix'));
        $article->setQuantite((int)$request->request->get('quantite'));
        
        // Gestion automatique du statut
        $quantite = (int)$request->request->get('quantite');
        $article->setStatut($quantite > 0 ? 'on_stock' : 'out_of_stock');

        // Gestion de l'image
        $imageFile = $request->files->get('url_image');
        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

            $destination = $this->getParameter('kernel.project_dir').'/public/image';
            $imageFile->move($destination, $newFilename);
            $article->setUrl_image('/image/'.$newFilename);
        }

        // Assignation des relations
        $article->setId_categorie($categorie);
        
        // Utilisateur par défaut (ID 62)
        $user = $em->getRepository(User::class)->find(62);
        if (!$user) {
            throw new \RuntimeException('Utilisateur par défaut non trouvé');
        }
        $article->setCreated_by($user);

        // Date de création
        $article->setCreatedAt(new \DateTime());

        $em->persist($article);
        $em->flush();

        $this->addFlash('success', 'Article créé avec succès');
        return $this->redirectToRoute('app_article_index');
    }

   
    return $this->render('article/index.html.twig', [
        'id_categorie' => $id_categorie,
        'categorie' => $categorie
    ]);
    
}

    #[Route('/{id_article}', name: 'app_article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/article/{id_article}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
public function edit(
    Request $request, 
    Article $article, 
    EntityManagerInterface $entityManager,
    SluggerInterface $slugger
): Response
{
    $form = $this->createForm(ArticleType::class, $article);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Gestion de l'image si nécessaire
        $imageFile = $form->get('url_image')->getData();
        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

            $destination = $this->getParameter('kernel.project_dir').'/public/image';
            $imageFile->move($destination, $newFilename);
            $article->setUrl_image('/image/'.$newFilename);
        }

        // Mise à jour du statut en fonction de la quantité
        $article->setStatut($article->getQuantite() > 0 ? 'on_stock' : 'out_of_stock');

        $entityManager->flush();

        $this->addFlash('success', 'Article modifié avec succès');
        return $this->redirectToRoute('app_article_index');
    }

    // Récupération de tous les articles pour l'affichage
    $articles = $entityManager->getRepository(Article::class)->findAll();

    return $this->render('article/index.html.twig', [
        'articles' => $articles,
        'articleToEdit' => $article, // Passer l'article à éditer pour le modal
        'formEdit' => $form->createView(),
    ]);
}

    #[Route('/{id_article}', name: 'app_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId_article(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }
}