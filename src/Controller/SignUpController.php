<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SignUpController extends AbstractController
{
    #[Route('/signup', name: 'app_sign_up', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        if ($request->isMethod('POST')) {
            $user = new User();
        
            // Set user properties from form data
            $user->setPrenom($request->request->get('prenom'));
            $user->setNom($request->request->get('nom'));
            $user->setCin($request->request->get('cin'));
            $user->setAdresse($request->request->get('adresse'));
            $user->setRole($request->request->get('role'));
            $user->setEmail($request->request->get('email'));
            $user->setNum_tel($request->request->get('num_tel'));
            $user->setUrl_image($request->request->get('url_image'));
            $user->setType_vehicule($request->request->get('type_vehicule'));
            $user->setVerified(false);
        
            $plainPassword = $request->request->get('password');
            $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);
        
            // Validation
            $errors = $validator->validate($user);
            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[] = $error->getMessage();
                }
        
                return $this->render('sign_up/signup.html.twig', [
                    'errors' => $errorMessages,
                ]);
            }
        
            $em->persist($user);
            $em->flush();
        
            $this->addFlash('success', 'Registration successful! You can now login.');
            return $this->redirectToRoute('app_login');
        }
        

        return $this->render('sign_up/signup.html.twig');
    }
}