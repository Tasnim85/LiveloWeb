<?php
// src/Controller/InvoiceController.php
namespace App\Controller;

use App\Entity\Commande;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class InvoiceController extends AbstractController
{
    #[Route('/order/{id}/invoice', name: 'app_order_invoice')]
    public function generateInvoice(Commande $commande, SessionInterface $session): Response
    {
        // 1. First try to get from completed orders
        $completedOrders = $session->get('completed_orders', []);
        $orderData = $completedOrders[$commande->getIdCommande()] ?? null;

        // 2. If not found, check pending order (for immediate download)
        if (!$orderData) {
            $orderData = $session->get('pending_order');
            if (!$orderData) {
                return $this->render('invoice/not_found.html.twig', [
                    'commande' => $commande
                ]);
            }
        }

        // 3. Prepare invoice data with fallbacks
        $invoiceData = $orderData['invoice_data'] ?? [
            'invoice_number' => 'INV-' . $commande->getIdCommande(),
            'created_at' => $commande->getHoraire() ?: new \DateTime(),
            'items' => [[
                'name' => 'Product information not available', 
                'price' => 0,
                'quantity' => 1,
                'total' => 0
            ]]
        ];

        $total = $orderData['total'] ?? array_reduce(
            $invoiceData['items'],
            fn($sum, $item) => $sum + $item['total'],
            0
        );

        // 4. Generate PDF
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        
        $dompdf = new Dompdf($pdfOptions);
        $dompdf->loadHtml($this->renderView('invoice/template.html.twig', [
            'commande' => $commande,
            'invoice_number' => $invoiceData['invoice_number'],
            'items' => $invoiceData['items'],
            'total' => $total,
            'date' => $invoiceData['created_at']
        ]));
        
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => sprintf(
                    'inline; filename="invoice-%s.pdf"',
                    $invoiceData['invoice_number']
                )
            ]
        );
    }
}