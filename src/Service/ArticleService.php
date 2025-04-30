<?php
namespace App\Service;

use App\Entity\User;
use App\Repository\ArticleRepository;

class ArticleService
{
    public function __construct(
        private ArticleRepository $articleRepo
    ) {}

    public function checkLowStock(User $user): array
    {
        return $this->articleRepo->findBelowThreshold($user, 3);
    }
}