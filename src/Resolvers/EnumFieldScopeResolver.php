<?php
 
namespace Safemood\MagicScopes\Resolvers;

use Illuminate\Database\Eloquent\Builder;
use Safemood\MagicScopes\Contracts\ScopeResolverContract;
use Illuminate\Support\Str;

class EnumFieldScopeResolver implements ScopeResolverContract
{
    public function matches(string $method, $model): bool
    {
        // Check if the method starts with 'is'
        return Str::startsWith($method, 'is');
    }

    public function apply(Builder $query, string $method, array $parameters, $model): Builder
    {
        $field = Str::snake(substr($method, 2));

        if (!empty($parameters)) {
            return $query->where($field, $parameters[0]);
        }

         return $query;
    }
}

