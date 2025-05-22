<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordResetController extends AbstractController
{
    #[Route('/api/request-password-reset', name: 'request_password_reset', methods: ['POST'])]
    public function requestPasswordReset(
        Request $request,
        UserRepository $userRepository,
        MailerInterface $mailer,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $user = $userRepository->findOneBy(['email' => $data['email']]);

        if (!$user) {
            return new JsonResponse(['error' => 'Utilisateur non trouvé'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Générer le token de réinitialisation
        $resetToken = bin2hex(random_bytes(32));
        $user->setResetToken($resetToken);
        $user->setResetTokenExpiresAt((new \DateTime())->modify('+1 hour')); // Expire dans 1 heure

        $entityManager->persist($user);
        $entityManager->flush();

        // Envoyer l'e-mail de réinitialisation
        $resetUrl = sprintf('%s/reset-password?token=%s', $this->getParameter('app.frontend_url'), $resetToken);
        $email = (new Email())
            ->from('elyayusd@gmail.com')
            ->to($user->getEmail())
            ->subject('Réinitialisation de mot de passe')
            ->text("Cliquez sur le lien suivant pour réinitialiser votre mot de passe : $resetUrl");

        $mailer->send($email);

        return new JsonResponse(['message' => 'E-mail de réinitialisation envoyé'], JsonResponse::HTTP_OK);
    }
    #[Route('/api/reset-password', name: 'reset_password', methods: ['POST'])]
    public function resetPassword(
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $token = $data['token'] ?? null;
        $newPassword = $data['newPassword'] ?? null;

        if (!$token || !$newPassword) {
            return new JsonResponse(['error' => 'Données invalides'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $user = $userRepository->findOneBy(['resetToken' => $token]);

        if (!$user || $user->getResetTokenExpiresAt() < new \DateTime()) {
            return new JsonResponse(['error' => 'Token invalide ou expiré'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Mettre à jour le mot de passe
        $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
        $user->setPassword($hashedPassword);

        // Réinitialiser le token et sa date d'expiration
        $user->setResetToken(null);
        $user->setResetTokenExpiresAt(null);

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Mot de passe réinitialisé avec succès'], JsonResponse::HTTP_OK);
    }
}
