<?php

namespace App\Controller;

use App\Form\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Commande;
use App\Entity\User;
use App\Entity\Article;
use App\Service\StripeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;


final class CheckoutController extends AbstractController
{


   // #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/checkout', name: 'app_checkout')]
public function checkout(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
{   if($this->getUser() == null){
    $this->addFlash('error', 'You must be logged in to proceed to checkout.');
    return $this->redirectToRoute('app_login');
}
        // 1. Check if cart is empty
    $panier = $session->get('panier', []);
    if (empty($panier)) {
        $this->addFlash('error', 'Your cart is empty');
        return $this->redirectToRoute('app_cart');
    }
    $cartItemCount = count($panier);
    // 2. Prepare cart data
    $panierWithData = [];
    foreach ($panier as $id => $quantity) {
        $article = $entityManager->getRepository(Article::class)->find($id);
        if ($article) {
            $panierWithData[] = [
                'article' => $article,
                'quantity' => $quantity,
                'cartItemCount' => $cartItemCount
            ];
        }
    }

    // 3. Calculate total
    $total = array_reduce($panierWithData, 
        fn($sum, $item) => $sum + ($item['article']->getPrix() * $item['quantity']), 
        0
    );

    // 4. Handle form submission
    $form = $this->createForm(OrderType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $formData = $form->getData();
        $paymentMethod = $form->get('paymentMethod')->getData();

        // 5. Prepare complete order data
        $invoiceNumber = 'INV-'.date('Ymd').'-'.uniqid();
        $now = new \DateTime();
        
        $orderData = [
            'form_data' => [
                'adresseDep' => $formData['adresseDep'],
                'adresseArr' => $formData['adresseArr'],
                'typeLivraison' => $formData['typeLivraison'],
                'paymentMethod' => $paymentMethod
            ],
            'cart_items' => array_values($panierWithData),
            'total' => (float)$total,
            'invoice_data' => [
                'invoice_number' => $invoiceNumber,
                'created_at' => $now,
                'items' => array_map(function($item) {
                    return [
                        'id' => $item['article']->getId_article(),
                        'name' => $item['article']->getNom(),
                        'price' => $item['article']->getPrix(),
                        'quantity' => $item['quantity'],
                        'total' => $item['article']->getPrix() * $item['quantity']
                    ];
                }, $panierWithData)
            ]
        ];

        // 6. Store in session
        $session->set('pending_order', $orderData);
        
        // 7. Also add to order history for future access
        $orderHistory = $session->get('order_history', []);
        $orderHistory[$invoiceNumber] = $orderData['invoice_data'];
        $session->set('order_history', $orderHistory);
        
        $session->save();

        // 8. Handle payment redirection
        if ($paymentMethod === 'online') {
            return $this->redirectToRoute('app_stripe_payment');
        } else {
            return $this->processOrder($entityManager, $session);
        }
    }

    // 9. Render checkout form
    return $this->render('checkout/index.html.twig', [
        'form' => $form->createView(),
        'items' => $panierWithData,
        'total' => $total
    ]);
}

#[Route('/process-order', name: 'app_process_order')]
public function processOrder(EntityManagerInterface $em, SessionInterface $session): Response
{
    $orderData = $session->get('pending_order');

    if (!$orderData) {
        $this->addFlash('error', 'No order to process');
        return $this->redirectToRoute('app_cart');
    }

    // Start transaction
    $em->getConnection()->beginTransaction();

    try {
        $formData = $orderData['form_data'];
        $invoiceData = $orderData['invoice_data'] ?? null;

        $commande = new Commande();
        $commande->setAdresseDep($formData['adresseDep']);
        $commande->setAdresseArr($formData['adresseArr']);
        $commande->setTypeLivraison($formData['typeLivraison']);
        $commande->setHoraire(new \DateTime());
        $commande->setStatut('Processing');

        // Verify user exists
        /*$user = $em->getRepository(User::class)->find(64); // Replace with actual user ID
        if (!$user) {
            throw new \Exception('User not found');
        }*/
        $user = $this->getUser(); // Get the currently authenticated user
        if (!$user) {
            throw new \Exception('User not authenticated');
        }
        $commande->setCreatedBy($user);

        $em->persist($commande);
        $em->flush();

        if (!$commande->getIdCommande()) {
            throw new \Exception('ID was not generated');
        }

        // 1. Update order history with commande ID before clearing session
        $orderHistory = $session->get('order_history', []);
        if ($invoiceData && isset($orderHistory[$invoiceData['invoice_number']])) {
            $orderHistory[$invoiceData['invoice_number']]['commande_id'] = $commande->getIdCommande();
            $session->set('order_history', $orderHistory);
        }

        // 2. Alternative: Store complete order data in separate session array
        $completedOrders = $session->get('completed_orders', []);
        $completedOrders[$commande->getIdCommande()] = [
            'invoice_data' => $invoiceData,
            'cart_items' => $orderData['cart_items'],
            'total' => $orderData['total'],
            'created_at' => new \DateTime()
        ];
        $session->set('completed_orders', $completedOrders);
        
        $em->getConnection()->commit();

        // 3. Clear only what's necessary
        $session->remove('panier');
        $session->remove('pending_order');
        $session->save();

        return $this->redirectToRoute('app_order_success', ['id' => $commande->getIdCommande()]);
    } catch (\Exception $e) {
        $em->getConnection()->rollBack();
        $this->addFlash('error', 'Order failed: ' . $e->getMessage());
        return $this->redirectToRoute('app_checkout');
    }
}

    #[Route('/stripe-payment', name: 'app_stripe_payment')]
    public function stripePayment(SessionInterface $session, UrlGeneratorInterface $urlGenerator): Response
    {
        // 1. Get and validate session data
        $order = $session->get('pending_order');

        if (!$order || !isset($order['form_data']['paymentMethod'])) {
            $this->addFlash('error', 'Invalid session data');
            return $this->redirectToRoute('app_checkout');
        }

        // 2. Initialize Stripe DIRECTLY (bypass service)
        \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);
        \Stripe\Stripe::setApiVersion('2023-08-16');

        // 3. Prepare line items
        $lineItems = [];
        foreach ($order['cart_items'] as $item) {
            if (!isset($item['article']) || !$item['article'] instanceof Article) {
                continue;
            }

            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item['article']->getNom(),
                    ],
                    'unit_amount' => (int)round(($item['article']->getPrix() * 100) ),
                ],
                'quantity' => $item['quantity'],
            ];
        }

        if (empty($lineItems)) {
            $this->addFlash('error', 'No valid items in cart');
            return $this->redirectToRoute('app_checkout');
        }

        // 4. Create Checkout Session
        try {
            $checkoutSession = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => $urlGenerator->generate('app_payment_success', [
                    'session_id' => $session->getId() // Use PHP session ID instead
                ], UrlGeneratorInterface::ABSOLUTE_URL),
                'cancel_url' => $urlGenerator->generate('app_checkout', [], UrlGeneratorInterface::ABSOLUTE_URL),
            ]);
    
            // Store BOTH Stripe session ID and PHP session ID
            $session->set('stripe_checkout_id', $checkoutSession->id);
            $session->set('php_session_id', $session->getId());
            $session->save();
    
            return $this->redirect($checkoutSession->url);
        } catch (\Exception $e) {
            $this->addFlash('error', 'Payment error: ' . $e->getMessage());
            return $this->redirectToRoute('app_checkout');
        }
    }

    #[Route('/payment-success', name: 'app_payment_success')]
        public function paymentSuccess(
            Request $request,
            EntityManagerInterface $em,
            SessionInterface $session
        ): Response {
        // Verify session consistency
        $requestSessionId = $request->query->get('session_id');
        $localPhpSessionId = $session->get('php_session_id');
        
        if ($requestSessionId !== $localPhpSessionId) {
            $this->addFlash('error', 'Session mismatch');
            return $this->redirectToRoute('app_checkout');
        }

        // Verify Stripe payment was completed
        \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);
        $stripeSessionId = $session->get('stripe_checkout_id');
        
        try {
            $checkoutSession = \Stripe\Checkout\Session::retrieve($stripeSessionId);
            
            if ($checkoutSession->payment_status !== 'paid') {
                $this->addFlash('error', 'Payment not completed');
                return $this->redirectToRoute('app_checkout');
            }

            // PROCESS THE ORDER HERE
            return $this->processOrder($em, $session);

        } catch (\Exception $e) {
            $this->addFlash('error', 'Payment verification failed: '.$e->getMessage());
            return $this->redirectToRoute('app_checkout');
        }
}

    #[Route('/order-success/{id}', name: 'app_order_success')]
    public function orderSuccess(Commande $commande): Response
    {
        return $this->render('checkout/success.html.twig', [
            'commande' => $commande
        ]);
    }
    #[Route('/debug-session', name: 'app_debug_session')]
    public function debugSession(SessionInterface $session): Response
    {
        dd([
            'pending_order' => $session->get('pending_order'),
            'all_session' => $session->all(),
            'session_id' => $session->getId()
        ]);
    }
    
}
