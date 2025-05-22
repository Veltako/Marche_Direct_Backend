<?php

namespace App\Controller;

use App\Repository\CommandeRepository;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

class CommandeController extends AbstractController
{
    #[Route('/api/commandes/user/{userId}', name: 'get_user_commands', methods: ['GET'])]
    public function getUserCommands($userId, CommandeRepository $commandeRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        // Récupérer l'id duu user
        $user = $entityManager->getRepository(User::class)->find($userId);

        if (!$user) {
            return new JsonResponse(['error' => 'Utilisateur non trouvé'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Récupérer les commandes du user
        $commands = $commandeRepository->findByUser($user);

        if (!$commands) {
            return new JsonResponse(['error' => 'Pas de commandes trouvées pour cet utilisateur'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Retour des commandes en format JSON
        return $this->json($commands, JsonResponse::HTTP_OK, [], ['groups' => ['read']]);
    }
}
