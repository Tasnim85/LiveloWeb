<?php
// src/Controller/OrdersController.php
namespace App\Controller;

use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ClientOrdersController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/my-orders', name: 'app_my_orders')]
    public function listOrders(): Response
    {
        // Get the user ID (replace 64 with your actual user ID or authentication logic)
        $userId = 64;
        
        // Get all orders for this user sorted by date
        $orders = $this->entityManager->getRepository(Commande::class)
            ->findBy(
                ['createdBy' => $userId],
                ['horaire' => 'DESC']
            );

        return $this->render('orders/list.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/order/{id}', name: 'app_order_details')]
    public function orderDetails(Commande $commande, SessionInterface $session): Response
    {
        // Try to get the complete order data from session
        $orderData = $session->get('completed_orders', [])[$commande->getIdCommande()] ?? null;

        return $this->render('orders/details.html.twig', [
            'commande' => $commande,
            'order_data' => $orderData,
        ]);
    }
}