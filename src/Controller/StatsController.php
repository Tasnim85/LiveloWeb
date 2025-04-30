<?php
namespace App\Controller;

use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatsController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/admin/stats', name: 'admin_stats')]
    public function dashboard(): Response
    {
        // 1. Top Users by Order Count
        $topUsers = $this->em->createQuery("
        SELECT u.idUser, CONCAT(u.nom, ' ', u.prenom) AS username, COUNT(c.idCommande) AS orderCount
        FROM App\Entity\Commande c
        JOIN c.createdBy u
        GROUP BY u.idUser, u.nom, u.prenom
        ORDER BY orderCount DESC
    ")->setMaxResults(5)->getResult();
    

        // 2. Orders by Status
        $statusStats = $this->em->createQuery("
            SELECT c.statut AS status, COUNT(c.idCommande) AS count
            FROM App\Entity\Commande c
            GROUP BY c.statut

        ")->getResult();

        // 3. Recent Orders
        $recentOrders = $this->em->getRepository(Commande::class)
            ->createQueryBuilder('c')
            ->join('c.createdBy', 'u')
            ->addSelect("CONCAT(u.nom, ' ', u.prenom) AS username")
            ->orderBy('c.horaire', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();

        // 4. Monthly Order Trends
        $monthlyTrends = $this->em->createQuery("
            SELECT 
                SUBSTRING(c.horaire, 1, 7) as month,
                COUNT(c.idCommande) as orderCount
            FROM App\Entity\Commande c
            GROUP BY month
            ORDER BY month DESC
        ")->setMaxResults(6)->getResult();
       // dd($recentOrders);
        //die;
        return $this->render('admin_commande/stats.html.twig', [
            'top_users' => $topUsers,
            'status_stats' => $statusStats,
            'recent_orders' => $recentOrders,
            'monthly_trends' => array_reverse($monthlyTrends), // Oldest first
        ]);
    }
}
?>