<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserImageController extends AbstractController
{
    #[Route('/api/upload/{id}', name: 'api_image_upload', methods: ['POST'])]
    public function uploadImage(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            return $this->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        $file = $request->files->get('file');

        if ($file) {
            $newFilename = uniqid() . '.' . $file->guessExtension();
            try {
                // Déplace le fichier dans le répertoire uploads
                $file->move($this->getParameter('uploads_directory'), $newFilename);

                // Met à jour l'entité utilisateur avec le nouveau nom de fichier
                $user->setImageFileName($newFilename);
                $entityManager->persist($user); // Persiste les changements
                $entityManager->flush(); // Envoie les changements à la base de données

            } catch (FileException $e) {
                return $this->json(['message' => 'Erreur lors du déplacement du fichier'], 500);
            }

            return $this->json(['message' => 'Fichier uploadé avec succès', 'filename' => $newFilename]);
        }

        return $this->json(['message' => 'Aucun fichier reçu'], 400);
    }

    #[Route('/api/upload/produit/{id}', name: 'api_image_upload_produit', methods: ['POST'])]
    public function uploadImageProduit(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $user = $entityManager->getRepository(Produit::class)->find($id);

        if (!$user) {
            return $this->json(['message' => 'Produit non trouvé'], 404);
        }

        $file = $request->files->get('file');

        if ($file) {
            $newFilename = uniqid() . '.' . $file->guessExtension();
            try {
                // Déplace le fichier dans le répertoire uploads
                $file->move($this->getParameter('uploads_directory'), $newFilename);

                // Met à jour l'entité utilisateur avec le nouveau nom de fichier
                $user->setImageFileName($newFilename);
                $entityManager->persist($user); // Persiste les changements
                $entityManager->flush(); // Envoie les changements à la base de données

            } catch (FileException $e) {
                return $this->json(['message' => 'Erreur lors du déplacement du fichier'], 500);
            }

            return $this->json(['message' => 'Fichier uploadé avec succès', 'filename' => $newFilename]);
        }

        return $this->json(['message' => 'Aucun fichier reçu'], 400);
    }
}
