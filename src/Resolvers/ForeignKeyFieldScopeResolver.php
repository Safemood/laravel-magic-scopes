<?php

declare(strict_types=1);

namespace Safemood\MagicScopes\Resolvers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Safemood\MagicScopes\Contracts\ScopeResolverContract;

class ForeignKeyFieldScopeResolver implements ScopeResolverContract
{
    public function matches(string $method, Model $model): bool
    {
        return (bool) preg_match('/^(with|for)([A-Z][a-zA-Z0-9]+)$/', $method);
    }

    public function apply(Builder $query, string $method, array $parameters, Model $model): Builder
    {
        if (! preg_match('/^(with|for)([A-Z][a-zA-Z0-9]+)$/', $method, $matches)) {
            return $query;
        }

        [, $prefix, $entity] = $matches;

        $relation = lcfirst($entity);
        $value = $parameters[0] ?? null;

        if (! method_exists($model, $relation)) {
            throw new \BadMethodCallException("Relation method '{$relation}' not defined on ".get_class($model));
        }

        return $prefix === 'with'
            ? $this->applyWith($query, $relation, $value, $model)
            : $this->applyFor($query, $relation, $value, $model);
    }

    protected function applyWith(Builder $query, string $relation, $value, $model): Builder
    {
        if (is_null($value)) {
            return $query->with($relation);
        }

        $relationInstance = $model->{$relation}();

        return $query->with([$relation => function ($relationQuery) use ($relationInstance, $value) {
            $builder = $relationQuery instanceof Relation
                ? $relationQuery->getQuery()
                : $relationQuery;

            $ownerKey = $this->resolveOwnerKey($relationInstance);

            if (is_array($value)) {
                $builder->whereIn($ownerKey, $value);
            } else {
                $builder->where($ownerKey, $value);
            }
        }]);
    }

    protected function applyFor(Builder $query, string $relation, $value, $model): Builder
    {
        if (is_null($value)) {
            return $query;
        }

        $relatedKey = $this->getRelatedForeignKeyName($model, $relation);

        return is_array($value)
            ? $query->whereIn($relatedKey, $value)
            : $query->where($relatedKey, $value);
    }

    protected function getRelatedForeignKeyName($model, string $relation): string
    {
        return $model->{$relation}()->getForeignKeyName();
    }

    protected function resolveOwnerKey(Relation $relation): string
    {
        if (method_exists($relation, 'getOwnerKeyName')) {
            return $relation->getOwnerKeyName();
        }

        if (method_exists($relation, 'getLocalKeyName')) {
            return $relation->getLocalKeyName();
        }

        return 'id';
    }
}
