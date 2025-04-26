<?php
namespace App\Repository;

use App\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categorie::class);
    }

    // Exemple de méthode pour récupérer des catégories
    public function findByCategorie($id_categorie)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.id = :idCategorie')
            ->setParameter('idCategorie', $id_categorie)
            ->getQuery()
            ->getOneOrNullResult();
    }
    
}
