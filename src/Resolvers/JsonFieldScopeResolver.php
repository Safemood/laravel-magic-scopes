<?php

namespace Safemood\MagicScopes\Resolvers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Safemood\MagicScopes\Contracts\ScopeResolverContract;

class JsonFieldScopeResolver implements ScopeResolverContract
{
    public function matches(string $method, $model): bool
    {
        return Str::endsWith($method, 'Json');
    }

    public function apply(Builder $query, string $method, array $parameters, $model): Builder
    {
        $field = Str::snake(substr($method, 0, -4));

        return $query->whereJsonContains($field, $parameters[0]);
    }
}
