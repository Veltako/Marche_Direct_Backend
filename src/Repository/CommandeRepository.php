<?php

namespace App\Repository;

use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;

/**
 * @extends ServiceEntityRepository<Commande>
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

     /**
     * Récupérer les commandes associées à un utilisateur
     * @param User $user
     * @return Commande[]
     */
    public function findByUser(User $user): array
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.UserCommande', 'u') // Joindre la relation UserCommande
            ->andWhere('u.id = :userId') // Filtrer par ID de l'utilisateur
            ->setParameter('userId', $user->getId())
            ->getQuery()
            ->getResult();
    }
}
