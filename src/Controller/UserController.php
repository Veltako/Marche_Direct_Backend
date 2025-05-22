<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/api/users/{id}', name: 'update_user', methods: ['PUT', 'PATCH'])]
    public function updateUser($id, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // Récupérer l'utilisateur par ID
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Récupérer les données 
        $data = json_decode($request->getContent(), true);

        // Mise à jour des champs du user
        if (isset($data['email'])) {
            $user->setEmail($data['email']);
        }
        if (isset($data['userName'])) {
            $user->setUserName($data['userName']);
        }
        if (isset($data['tel'])) {
            $user->setTel($data['tel']); 
        }
        if (isset($data['nameBusiness'])) {
            $user->setNameBusiness($data['nameBusiness']);
        }
        if (isset($data['imageFileName'])) {
            $user->setImageFileName($data['imageFileName']);
        }
        if (isset($data['descriptionCommerce'])) {
            $user->setDescriptionCommerce($data['descriptionCommerce']);
        }
        if (isset($data['numSiret'])) {
            $user->setNumSiret($data['numSiret']);
        }

        // Sauvegarde des changements
        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Les infos utilisateur ont été mises à jour !'], JsonResponse::HTTP_OK);
    }
}
