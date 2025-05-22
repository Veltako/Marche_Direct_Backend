<?php

namespace App\Filter;

use ApiPlatform\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PropertyInfo\Type;

class UserFilter extends AbstractFilter
{
    private array $relations = ['user'];

    public function __construct(ManagerRegistry $managerRegistry, array $relations = ['user'])
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

        // Obtenez l'alias de la racine de la requête
        $alias = $queryBuilder->getRootAliases()[0];
        $relatedAlias = $queryNameGenerator->generateJoinAlias($property);

        // Vérification de la valeur de filtre
        if (is_array($value)) {
            $queryBuilder
                ->join(sprintf('%s.%s', $alias, $property), $relatedAlias)
                ->andWhere(sprintf('%s.id IN (:userIds)', $relatedAlias))
                ->setParameter('userIds', $value);
        } else {
            $queryBuilder
                ->join(sprintf('%s.%s', $alias, $property), $relatedAlias)
                ->andWhere(sprintf('%s.id = :userId', $relatedAlias))
                ->setParameter('userId', $value);
        }
    }

    public function getDescription(string $resourceClass): array
    {
        return [
            'user' => [
                'property' => null,
                'type' => Type::BUILTIN_TYPE_INT,
                'required' => false,
                'description' => 'Filter by User ID',
            ],
        ];
    }

    protected function splitPropertyParts(string $property, string $resourceClass): array
    {
        // Divise le nom de la propriété sur les points
        return explode('.', $property);
    }
}
