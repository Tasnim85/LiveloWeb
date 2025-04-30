<?php
// src/Controller/StatistiqueController.php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatistiqueController extends AbstractController
{
    #[Route('/statistiques/articles', name: 'article_stats')]
    public function index(ArticleRepository $articleRepository): Response
    {
        $stats = $articleRepository->countArticlesByStockStatus();

        return $this->render('statistiques/index.html.twig', [
            'stats' => $stats
        ]);
    }
}

