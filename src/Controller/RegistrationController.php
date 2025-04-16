<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'user_register')]
    public function register(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher,
        ValidatorInterface $validator
    ): Response {
        $user = new User();

        if ($request->isMethod('POST')) {
            $user->setNom($request->request->get('nom'));
            $user->setPrenom($request->request->get('prenom'));
            $user->setCin($request->request->get('cin'));
            $user->setAdresse($request->request->get('adresse'));
            $user->setEmail($request->request->get('email'));
            $user->setNumTel($request->request->get('num_tel'));
            $user->setUrlImage($request->request->get('url_image'));
            $user->setRole($request->request->get('role'));
            $user->setType_vehicule($request->request->get('type_vehicule') ?: null);
            $user->setVerified(false);

            $plainPassword = $request->request->get('password');
            $confirmPassword = $request->request->get('confirm_password');

            if ($plainPassword !== $confirmPassword) {
                return $this->render('registration/register.html.twig', [
                    'errors' => ['Passwords do not match.'],
                    'data' => $request->request->all(),
                ]);
            }

            $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);

            $errors = $validator->validate($user);
            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[] = $error->getMessage();
                }

                return $this->render('registration/register.html.twig', [
                    'errors' => $errorMessages,
                    'data' => $request->request->all(),
                ]);
            }

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Your account is created successfully !');

            return $this->redirectToRoute('user_register');
        }

        return $this->render('registration/register.html.twig', [
            'data' => [], 
        ]);
    }
}
