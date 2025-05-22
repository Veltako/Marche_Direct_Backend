<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use App\Repository\CommercantRepository;
use App\Repository\MarcheRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    private $produitRepository;
    private $commercantRepository;
    private $marcheRepository;

    public function __construct(ProduitRepository $produitRepository, UserRepository $commercantRepository, MarcheRepository $marcheRepository)
    {
        $this->produitRepository = $produitRepository;
        $this->commercantRepository = $commercantRepository;
        $this->marcheRepository = $marcheRepository;
    }

    #[Route('/api/search', name: 'api_search', methods: ['GET'])]
    public function search(Request $request): JsonResponse
    {
        $term = $request->query->get('q');

        if (empty($term)) {
            return new JsonResponse(['error' => 'Search term cannot be empty'], 400);
        }

        // Recherche des produits
        $produits = $this->produitRepository->findByTerm($term);
        
        // Recherche des marchés
        $marches = $this->marcheRepository->findByTerm($term);
        
        // Recherche des commerçants
        $commercants = $this->commercantRepository->findByTerm($term);

        // Formatage des résultats pour les produits
        $produitsArray = array_map(function ($produit) {
            return [
                'id' => $produit->getId(),
                'productName' => $produit->getProductName(),
                'imageFileName' => $produit->getImageFileName(),
                'userProductId' => $produit->getUserProduct() ? $produit->getUserProduct()->getId() : null,
            ];
        }, $produits);

        // Formatage des résultats pour les marchés
        $marchesArray = array_map(function ($marche) {
            return [
                'id' => $marche->getId(),
                'marcheName' => $marche->getMarcheName(),
                'imageFileName' => $marche->getImageFileName(),
            ];
        }, $marches);

        // Formatage des résultats pour les commerçants
        $commercantsArray = array_map(function ($commercant) {
            return [
                'id' => $commercant->getId(),
                'nameBusiness' => $commercant->getNameBusiness(),
                'imageFileName' => $commercant->getImageFileName(),
            ];
        }, $commercants);

        return new JsonResponse([
            'produits' => $produitsArray,
            'marches' => $marchesArray,
            'commercants' => $commercantsArray,
        ]);
    }
}

