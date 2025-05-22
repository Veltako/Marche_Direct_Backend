<?php
namespace App\Controller;

use App\Service\SendEmailService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact', methods: ['POST'])]
    public function sendContactEmail(Request $request, SendEmailService $mail): JsonResponse
    {
        // Récupération des données de la requête
        $data = json_decode($request->getContent(), true);

        // Vérification que les champs requis sont présents
        if (!isset($data['userName'], $data['email'], $data['comment'], $data['nameBusiness'])) {
            return new JsonResponse(['error' => 'Données invalides'], Response::HTTP_BAD_REQUEST);
        }

        // Appel du service pour envoyer l'email
        $mail->send(
            $data['email'], // Expéditeur
            'elyayusd@gmail.com', // Destinataire
            'Retour d\'un utilisateur', // Sujet de l'email
            'contact', // Nom du template
            [ // Contexte pour le template
                'userName' => $data['userName'],
                'comment' => $data['comment'],
                'nameBusiness' => $data['nameBusiness'],
            ]
        );

        // Réponse de succès
        return new JsonResponse(['message' => 'Formulaire correctement envoyé'], Response::HTTP_OK);
    }
}
