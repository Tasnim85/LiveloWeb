<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    // Méthode personnalisée pour récupérer les articles d'une catégorie
    public function findByCategorie($categorieId)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.id_categorie = :categorieId')
            ->setParameter('categorieId', $categorieId)
            ->getQuery()
            ->getResult();
    }
}
