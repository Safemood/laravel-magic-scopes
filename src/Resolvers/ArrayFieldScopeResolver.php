<?php

namespace Safemood\MagicScopes\Resolvers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Safemood\MagicScopes\Contracts\ScopeResolverContract;

class ArrayFieldScopeResolver implements ScopeResolverContract
{
    public function matches(string $method, $model): bool
    {
        return Str::startsWith($method, 'with') && Str::endsWith($method, 'Array');
    }

    public function apply(Builder $query, string $method, array $parameters, $model): Builder
    {
        $field = Str::snake(substr($method, 4, -5));

        return $query->whereJsonContains($field, $parameters[0]);
    }
}
