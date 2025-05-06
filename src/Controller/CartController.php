<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\BrowserKit\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;

final class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(SessionInterface $session,EntityManagerInterface $em)
    {
        $panier=$session->get('panier', []);
        $cartItemCount = count($panier);
        $panierWithData = [];
        foreach($panier as $id => $quantity) {
            $panierWithData[] = [
                'article' => $em->getRepository(Article::class)->find($id),
               'quantity' => $quantity
            ];
        }
        //dd($panierWithData);
        $total=0;
        foreach($panierWithData as $item) {
            $totalItem = $item['article']->getPrix() * $item['quantity'];
            $total += $totalItem;
        }
        return $this->render('cart/index.html.twig', [
            "items"=>$panierWithData,
            "total"=>$total,
            'cartItemCount' => $cartItemCount,
        ]);
    }

    #[Route('/cart/add/{articleId}', name: 'app_cart_add')]
    /**
     * @param int $articleId
     * @param Request $request
     */
    // This method is responsible for adding an article to the cart
    public function addToCart($articleId,SessionInterface $session,EntityManagerInterface $em,Request $request)
    {
        //dd($articleId);
        $article = $em->getRepository(Article::class)->find($articleId);
        if(!$article) {
            throw $this->createNotFoundException('Article not found');
        }
        $panier = $session->get('panier', []);
        if(isset($panier[$articleId])) {
            $panier[$articleId]++;
        } else {
            $panier[$articleId] = 1;
        }
        //$panier [$articleId] = 1;
        $session->set('panier', $panier);
        //dd($panier);
        if ($request->isXmlHttpRequest()) {
            // If it's an AJAX request, return JSON instead of redirect
            return new JsonResponse([
                'success' => true,
                'message' => 'Added Successfully!'
            ]);
        }
        $this->addFlash('success', 'Added Successfully !');
        return $this->redirectToRoute('app_articles_list');

    }
    #[Route('/cart/remove/{articleId}', name: 'app_cart_remove')]
    public function removeFromCart($articleId,SessionInterface $session)
    {
       
        $panier = $session->get('panier', []);
        if(!empty($panier[$articleId])) {
            unset($panier[$articleId]);
        }
        $session->set('panier', $panier);
        return $this->redirectToRoute('app_cart');
    }
    
    
    
    #[Route('/cart/increase/{articleId}', name: 'app_cart_increase')]
    public function increaseQuantity($articleId, SessionInterface $session, EntityManagerInterface $em)
    {
        $article = $em->getRepository(Article::class)->find($articleId);
        if (!$article) {
            $this->addFlash('error', 'Article not found.');
            return $this->redirectToRoute('app_cart');
        }
    
        $stock = $article->getQuantite();
        $panier = $session->get('panier', []);
    
        if (isset($panier[$articleId])) {
            if ($panier[$articleId] < $stock) {
                $panier[$articleId]++;
            } else {
                $this->addFlash('error', 'Stock limit reached for this item.');
            }
        } else {
            $panier[$articleId] = 1;
        }
    
        $session->set('panier', $panier);
        return $this->redirectToRoute('app_cart');
    }
        #[Route('/cart/decrease/{articleId}', name: 'app_cart_decrease')]
    public function decreaseQuantity($articleId, SessionInterface $session)
    {
    $panier = $session->get('panier', []);
    if (isset($panier[$articleId]) && $panier[$articleId] > 1) {
        $panier[$articleId]--;
    } else {
        unset($panier[$articleId]); // Optionally remove item when quantity reaches 0
    }
    $session->set('panier', $panier);
    return $this->redirectToRoute('app_cart');
    }
}
