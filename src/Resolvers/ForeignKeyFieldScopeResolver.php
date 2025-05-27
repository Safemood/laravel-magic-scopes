<?php

namespace Safemood\MagicScopes\Resolvers;

use Illuminate\Database\Eloquent\Builder;
use Safemood\MagicScopes\Contracts\ScopeResolverContract;

class ForeignKeyFieldScopeResolver implements ScopeResolverContract
{
    public function matches(string $method, $model): bool
    {
        return preg_match('/^(with|for)([A-Z][a-zA-Z0-9]+)$/', $method);
    }

    public function apply(Builder $query, string $method, array $parameters, $model): Builder
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

        if ($prefix === 'with') {
            return $this->applyWith($query, $relation, $value, $model);
        }

        return $this->applyFor($query, $relation, $value, $model);
    }

    protected function applyWith(Builder $query, string $relation, $value, $model): Builder
    {
        if (is_null($value)) {
            return $query->with($relation);
        }

        $related = $model->{$relation}()->getRelated();
        $relatedKey = $related->getKeyName(); // typically 'id'

        return $query->whereHas($relation, function (Builder $q) use ($relatedKey, $value) {
            if (is_array($value)) {
                $q->whereIn($relatedKey, $value);
            } else {
                $q->where($relatedKey, $value);
            }
        })->with([$relation => function (Builder $q) use ($relatedKey, $value) {
            if (is_array($value)) {
                $q->whereIn($relatedKey, $value);
            } else {
                $q->where($relatedKey, $value);
            }
        }]);
    }

    protected function applyFor(Builder $query, string $relation, $value, $model): Builder
    {
        if (is_null($value)) {
            return $query;
        }

        $relatedKey = $this->getRelatedForeignKeyName($model, $relation);

        if (is_array($value)) {
            return $query->whereIn($relatedKey, $value);
        }

        return $query->where($relatedKey, $value);
    }

    protected function getRelatedForeignKeyName($model, string $relation)
    {
        if (method_exists($model, $relation)) {
            $relationObj = $model->{$relation}();

            return $relationObj->getForeignKeyName();
        }

        return $relation.'_id';
    }
}
