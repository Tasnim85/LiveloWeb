<?php

namespace App\Controller;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use App\Entity\Article;
use App\Service\ArticleService;
use App\Entity\User;
use App\Service\TwilioService;
use App\Entity\Categorie;  
use Symfony\Component\Security\Core\Security;
use Knp\Component\Pager\PaginatorInterface;

use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Form\CategorieType;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\SerializerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

#[Route('/article')]
final class ArticleController extends AbstractController
{

    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

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



#[Route('/artPartner/{id_categorie}/articles', name: 'art_partner_articles', methods: ['GET'])]
public function artPartnerArticles(int $id_categorie, EntityManagerInterface $entityManager): Response
{
    // Récupérer la catégorie par son ID
    $categorie = $entityManager->getRepository(Categorie::class)->find($id_categorie);
    
    if (!$categorie) {
        throw $this->createNotFoundException('Catégorie introuvable');
    }

    // Récupérer les articles associés à cette catégorie
    $articles = $entityManager->getRepository(Article::class)->findBy([
        'id_categorie' => $categorie
    ]);

    // Calculer le total gain
    $totalGain = 0;
    foreach ($articles as $article) {
        $totalGain += $article->getPrix() * $article->getQuantite();
    }

    return $this->render('article/artPartner.html.twig', [
        'categorie' => $categorie,
        'articles' => $articles,
        'totalGain' => $totalGain,  // Passer le total gain à la vue
    ]);
}





#[Route('/article/new/{id_categorie}', name: 'app_article_new', methods: ['GET', 'POST'])]
public function new(
    Request $request,
    EntityManagerInterface $em,
    SluggerInterface $slugger,
    int $id_categorie,
    TwilioService $twilioService // Injection du service Twilio
): Response {
    // Vérification de l'existence de la catégorie
    $categorie = $em->getRepository(Categorie::class)->find($id_categorie);
    if (!$categorie) {
        throw $this->createNotFoundException('Catégorie non trouvée');
    }

    $article = new Article();
    $form = $this->createForm(ArticleType::class, $article);

    if ($request->isMethod('POST')) {
        dump("Le formulaire a été soumis !");
    }

    $form->handleRequest($request);

    if ($form->isSubmitted()) {
        if (!$form->isValid()) {
            dump("Le formulaire n'est pas valide !");
            foreach ($form->getErrors(true) as $error) {
                dump($error->getMessage());
            }
        } else {
            $article->setId_categorie($categorie);
            $article->setNom($form->get('nom')->getData());
            $article->setDescription($form->get('description')->getData());
            $article->setPrix((float)$form->get('prix')->getData());
            $article->setQuantite((int)$form->get('quantite')->getData());
            $article->setStatut($article->getQuantite() > 0 ? 'on_stock' : 'out_of_stock');

            $imageFile = $form->get('url_image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                $destination = $this->getParameter('kernel.project_dir').'/public/image';
                $imageFile->move($destination, $newFilename);
                $article->setUrl_image('/image/'.$newFilename);
            }

            $user = $this->getUser();

            if (!$user) {
                throw $this->createAccessDeniedException('User not authenticated.');
            }
            
            $article->setCreated_by($user);
            
            // Si tu veux vraiment extraire l'ID pour autre chose :
            $userData = json_decode(
                $this->serializer->serialize($user, 'json', ['groups' => 'user:read']),
                true
            );
            
            $userId = $userData['idUser'];
            dump("ID de l'utilisateur connecté : " . $userId);
            
            
            $article->setCreatedAt(new \DateTime());

            $em->persist($article);
            $em->flush();

            // Envoi du SMS après ajout de l'article
            $numeroClient = '+21652887287'; // Remplace par un numéro dynamique si besoin
            $numeroTwilio = '+15154171765'; // Ton numéro Twilio
            $message = 'Un nouvel article a été ajouté : ' . $article->getNom();

            $twilioService->sendSms($numeroClient, $numeroTwilio, $message);

            $this->addFlash('success', 'Article créé avec succès');
            return $this->redirectToRoute('app_article_index');
        }
    }

    return $this->render('article/new.html.twig', [
        'form' => $form->createView(),
        'categorie' => $categorie,
    ]);
}





    


  
 

    #[Route('/article/{id_article}/edit', name: 'app_article_edit')]
public function edit(
    Request $request,
    ArticleRepository $articleRepository,
    EntityManagerInterface $em,
    SluggerInterface $slugger,
    int $id_article
): Response {
    $article = $articleRepository->find($id_article);

    if (!$article) {
        throw $this->createNotFoundException('Article introuvable.');
    }

    $form = $this->createForm(ArticleType::class, $article);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Mise à jour des champs principaux
        $article->setNom($form->get('nom')->getData());
        $article->setDescription($form->get('description')->getData());
        $article->setPrix((float)$form->get('prix')->getData());
        $article->setQuantite((int)$form->get('quantite')->getData());

        // Mettre à jour automatiquement le statut
        $quantite = (int)$form->get('quantite')->getData();
        $article->setStatut($quantite > 0 ? 'on_stock' : 'out_of_stock');

        // Gestion de l'image si elle a été modifiée
        $imageFile = $form->get('url_image')->getData();

        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

            $destination = $this->getParameter('kernel.project_dir').'/public/image';
            try {
                $imageFile->move($destination, $newFilename);
                $article->setUrl_image('/image/'.$newFilename);
            } catch (FileException $e) {
                $this->addFlash('danger', 'Erreur lors de l\'upload de l\'image.');
            }
        }

        $em->flush();

        $this->addFlash('success', 'Article mis à jour avec succès !');
        return $this->redirectToRoute('app_article_index');
    }

    return $this->render('article/edit.html.twig', [
        'form' => $form->createView(),
        'article' => $article,
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
    #[Route('/client/articles', name: 'app_articles_list')]
    public function index1(SessionInterface $session, EntityManagerInterface $entityManager, Request $request, PaginatorInterface $paginator): Response
    {
        $panier=$session->get('panier', []);
        $cartItemCount = count($panier);
        // Récupérer la query
        $query = $entityManager->getRepository(Article::class)
            ->createQueryBuilder('a') // a est un alias
            ->getQuery();

        // Paginer la query
        $articles = $paginator->paginate(
            $query, /* query pas findAll() */
            $request->query->getInt('page', 1), /* numéro de page */
            9 /* limite d'articles par page */
        );

        return $this->render('article/list.html.twig', [
            'articles' => $articles,
            'cartItemCount' => $cartItemCount,
        ]);
    }
}
