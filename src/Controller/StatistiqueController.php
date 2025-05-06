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
use Symfony\Component\Serializer\SerializerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;


class StatistiqueController extends AbstractController
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

   
      
    #[Route('/statistiques/chart-partial', name: 'article_stats_partial')]
public function chartPartial(ArticleRepository $articleRepository): Response
{
    $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('User not authenticated.');
        }
        
       
        
        // Si tu veux vraiment extraire l'ID pour autre chose :
        $userData = json_decode(
            $this->serializer->serialize($user, 'json', ['groups' => 'user:read']),
            true
        );
        
        $userId = $userData['idUser'];
        dump("ID de l'utilisateur connecté : " . $userId);

    $rawStats = $articleRepository->countArticlesByStockStatusForUser($user);

    $stats = [['Statut', 'Nombre']];
    foreach ($rawStats as $row) {
        $stats[] = [$row['statut'], (int)$row['count']];
    }

    return $this->render('statistiques/chart-partial.html.twig', [
        'stats' => $stats
    ]);
}

    
}

