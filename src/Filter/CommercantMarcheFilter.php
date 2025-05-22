<?php

namespace App\Filter;

use ApiPlatform\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PropertyInfo\Type;

class CommercantMarcheFilter extends AbstractFilter
{
    private array $relations;

    public function __construct(ManagerRegistry $managerRegistry, array $relations = ['commercant_marche'])
    {
        parent::__construct($managerRegistry);
        $this->relations = $relations;
    }

    protected function filterProperty(
        string $property,
        $value,
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        ?Operation $operation = null,
        array $context = []
    ): void {
        // Vérifiez si la propriété est celle que nous voulons filtrer
        if (!in_array($property, $this->relations)) {
            return;
        }

        $alias = $queryBuilder->getRootAliases()[0];
        $relatedAlias = $queryNameGenerator->generateJoinAlias($property);

        // Appliquez le filtre par ID utilisateur
        $queryBuilder
            ->join(sprintf('%s.%s', $alias, $property), $relatedAlias)
            ->andWhere(sprintf('%s.id = :userId', $relatedAlias)) // Assurez-vous d'utiliser 'id' ici
            ->setParameter('userId', $value);
    }

    public function getDescription(string $resourceClass): array
    {
        return [
            'commercant_marche' => [
                'property' => null,
                'type' => Type::BUILTIN_TYPE_INT,
                'required' => false,
                'description' => 'Filter by Commercant User ID',
            ],
        ];
    }

    protected function splitPropertyParts(string $property, string $resourceClass): array
    {
        return explode('.', $property);
    }
}
