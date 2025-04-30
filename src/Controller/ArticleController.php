<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ArticleController extends AbstractController
{
    #[Route('/client/articles', name: 'app_articles_list')]
    public function index(EntityManagerInterface $entityManager, Request $request, PaginatorInterface $paginator): Response
    {
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
            'articles' => $articles
        ]);
    }
}
