<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\UsersAuthenticator;
use App\Service\SendEmailService;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods: ['POST'])]
    public function register(
        Request $request, 
        UserPasswordHasherInterface $passwordHasher, 
        EntityManagerInterface $entityManager,
        MailerInterface $mailer,
        SendEmailService $mail,
        UserAuthenticatorInterface $userAuthenticator,
        UsersAuthenticator $authenticator,
        JWTTokenManagerInterface $jwtManager,
    ): Response {
        $data = json_decode($request->getContent(), true);

        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;
        $username = $data['username'] ?? null;
        $tel = $data['tel'] ?? null;

        if (!$email || !$password || !$username || !$tel) {
            return new JsonResponse(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
        }

        $user = new User();
        $user->setEmail($email);
        $hashedPassword = $passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);
        $user->setUserName($username);
        $user->setTel($tel);
        $user->setDateDeCreation(new \DateTime());
        $user->setImageFileName("images/profil.png");

        $entityManager->persist($user);
        $entityManager->flush();

            // Génération du token JWT directement avec le JWTManager
            $token = $jwtManager->create($user);

            // Envoyer l'e-mail
            $mail->send(
                'elyayusd@gmail.com',
                $user->getEmail(),
                'Activation de votre compte sur le site Marché Direct',
                'register',
                compact('user', 'token') // ['user' => $user, 'token'=>$token]
            );

        $this->addFlash('success', 'Utilisateur inscrit, veuillez cliquer sur le lien reçu pour confirmer votre adresse e-mail');

        return $userAuthenticator->authenticateUser(
            $user,
            $authenticator,
            $request
        );
    }
}