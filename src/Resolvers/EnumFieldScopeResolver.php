<?php

declare(strict_types=1);

namespace Safemood\MagicScopes\Resolvers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Safemood\MagicScopes\Contracts\ScopeResolverContract;

class EnumFieldScopeResolver implements ScopeResolverContract
{
    public function matches(string $method, Model $model): bool
    {
        return Str::endsWith($method, 'Is') || Str::endsWith($method, 'IsNot');
    }

    public function apply(Builder $query, string $method, array $parameters, Model $model): Builder
    {
        if (empty($parameters)) {
            throw new \InvalidArgumentException("EnumFieldScopeResolver requires parameters for method [$method].");
        }

        $isNegative = Str::endsWith($method, 'IsNot');
        $suffix = $isNegative ? 'IsNot' : 'Is';
        $field = Str::snake(Str::beforeLast($method, $suffix));
        $operator = $isNegative ? '!=' : '=';

        return $query->where($field, $operator, $parameters[0]);
    }

    protected function getPositiveScopeName(string $field): string
    {
        return Str::camel($field).'Is';
    }

    protected function getNegativeScopeName(string $field): string
    {
        return Str::camel($field).'IsNot';
    }
}
