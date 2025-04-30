<?php
namespace App\Service;

use App\Repository\ArticleRepository;

class StatistiqueService
{
    private $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function getStockStats(): array
    {
        $articles = $this->articleRepository->findAll();

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
