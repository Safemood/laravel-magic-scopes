<?php

namespace Safemood\MagicScopes\Resolvers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Safemood\MagicScopes\Contracts\ScopeResolverContract;

class NumberFieldScopeResolver implements ScopeResolverContract
{
    public function matches(string $method, $model): bool
    {
        return Str::endsWith($method, 'Count') || Str::endsWith($method, 'Total');
    }

    public function apply(Builder $query, string $method, array $parameters, $model): Builder
    {
        $field = Str::snake(substr($method, 0, -5));

        return $query->where($field, '>=', $parameters[0]);
    }
}
