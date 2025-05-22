<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface; // Importer l'EntityManagerInterface

class CommentController extends AbstractController
{
    #[Route('/api/comments/user/{userId}', name: 'get_user_comments', methods: ['GET'])]
    public function getUserComments($userId, CommentRepository $commentRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        // Récupérer l'utilisateur par l'id
        $user = $entityManager->getRepository(User::class)->find($userId);

        if (!$user) {
            return new JsonResponse(['error' => 'Utilisateur non trouvé'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Récupérer les commentaires liés à l'utilisateur
        $comments = $commentRepository->findByUser($user); // Utilisation de la nouvelle méthode

        if (!$comments) {
            return new JsonResponse(['error' => 'Pas de commentaires trouvés pour cet utilisateur'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Retourner les commentaires en format JSON
        return $this->json($comments, JsonResponse::HTTP_OK, [], ['groups' => ['read']]);
    }
}
