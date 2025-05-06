<?php
namespace App\Service;

use App\Repository\ArticleRepository;
use App\Entity\User;

class StatistiqueService
{
    private ArticleRepository $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function getStockStats(User $user): array
    {
        // Récupère uniquement les articles de l'utilisateur
        $articles = $this->articleRepository->findBy(['created_by' => $user]);

        $onStock = 0;
        $outOfStock = 0;

        foreach ($articles as $article) {
            if ($article->getQuantite() > 0) {
                $onStock++;
            } else {
                $outOfStock++;
            }
        }

        return [
            ['Statut', 'Nombre'],
            ['En stock', $onStock],
            ['Rupture de stock', $outOfStock]
        ];
    }
}
