<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class NotVerifiedUsersController extends AbstractController
{
    #[Route('/notVerifiedUsers', name: 'app_not_verified_users')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(User::class)->findBy(['verified' => 0]);

        return $this->render('not_verified_users/index.html.twig', [
            'users' => $users,
        ]);
    }
    #[Route('/user/accept/{id}', name: 'app_user_accept')]
    public function accept(int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $user->setVerified(true);
        $entityManager->flush();

        return $this->redirectToRoute('app_not_verified_users');
    }
    #[Route('/user/delete/{id}', name: 'app_user_delete')]
    public function delete(int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_not_verified_users');
    }


}