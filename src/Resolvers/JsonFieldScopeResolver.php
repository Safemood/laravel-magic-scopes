<?php

declare(strict_types=1);

namespace Safemood\MagicScopes\Resolvers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Safemood\MagicScopes\Contracts\ScopeResolverContract;

class JsonFieldScopeResolver implements ScopeResolverContract
{
    public function matches(string $method, Model $model): bool
    {
        return Str::endsWith($method, ['Contains', 'DoesntContain']);
    }

    public function apply(Builder $query, string $method, array $parameters, Model $model): Builder
    {
        $value = $parameters[1] ?? null;
        $jsonKey = $parameters[0] ?? null;

        if (! $jsonKey || is_null($value)) {
            throw new \InvalidArgumentException('Both JSON key and value must be provided.');
        }

        $mode = Str::endsWith($method, 'DoesntContain') ? 'whereJsonDoesntContain' : 'whereJsonContains';
        $column = Str::beforeLast($method, Str::endsWith($method, 'DoesntContain') ? 'DoesntContain' : 'Contains');

        $column = Str::snake($column);

        $path = "$column->$jsonKey";

        return $query->$mode($path, $value);
    }
}
