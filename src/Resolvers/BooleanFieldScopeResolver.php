<?php

declare(strict_types=1);

namespace Safemood\MagicScopes\Resolvers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Safemood\MagicScopes\Contracts\ScopeResolverContract;

class BooleanFieldScopeResolver implements ScopeResolverContract
{
    public function matches(string $method, Model $model): bool
    {

        return collect($model->getFillable())->contains(function ($field) use ($method) {
            return $this->getPositiveScopeName($field) === $method
                || $this->getNegativeScopeName($field) === $method;
        });
    }

    public function apply(Builder $query, string $method, array $parameters, Model $model): Builder
    {

        $field = $this->resolveBooleanField($method, $model);

        $value = Str::startsWith($method, ['not', 'hasNot']) ? false : true;

        $query->where($field, $value);

        return $query;
    }

    protected function resolveBooleanField(string $method, Model $model): string
    {
        return collect($model->getFillable())->first(function ($field) use ($method) {
            return $this->getPositiveScopeName($field) === $method
                || $this->getNegativeScopeName($field) === $method;
        });
    }

    protected function getPositiveScopeName(string $field): string
    {
        if (Str::startsWith($field, 'is_')) {
            return Str::camel(substr($field, 3));
        }

        if (Str::startsWith($field, 'has_')) {
            return Str::camel($field);
        }

        return '';
    }

    protected function getNegativeScopeName(string $field): string
    {
        $positive = $this->getPositiveScopeName($field);

        if (Str::startsWith($field, 'has_')) {
            return $positive ? 'hasNot'.Str::ucfirst(Str::camel(substr($field, 4))) : '';
        }

        return $positive ? 'not'.ucfirst($positive) : '';
    }
}
