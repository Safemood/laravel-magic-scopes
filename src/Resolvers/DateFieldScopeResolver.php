<?php

namespace Safemood\MagicScopes\Resolvers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Safemood\MagicScopes\Contracts\ScopeResolverContract;

class DateFieldScopeResolver implements ScopeResolverContract
{
    public function matches(string $method, $model): bool
    {
        return (bool) preg_match('/^(?!where).*(At|Before|After|Between)$/', $method);
    }

    public function apply(Builder $query, string $method, array $parameters, $model): Builder
    {
        $suffix = Str::match('/^(?!where).*(At|Before|After|Between)$/', $method);
        $field = Str::snake(Str::beforeLast($method, $suffix));

        if (! Str::endsWith($field, '_at')) {
            $field .= '_at';
        }

        if (empty($parameters)) {
            throw new \InvalidArgumentException("DateFieldScopeResolver requires parameters for method [$method].");
        }

        return match ($suffix) {
            'At' => $query->whereDate($field, $parameters[0]),
            'Before' => $query->whereDate($field, '<', $parameters[0]),
            'After' => $query->whereDate($field, '>', $parameters[0]),
            'Between' => $this->applyBetween($query, $field, $parameters),
            default => throw new \InvalidArgumentException("Unknown date scope suffix [$suffix]."),
        };
    }

    protected function applyBetween(Builder $query, string $field, array $parameters): Builder
    {
        if (count($parameters) < 2) {
            throw new \InvalidArgumentException("DateFieldScopeResolver 'Between' requires two date parameters.");
        }

        return $query->whereBetween($field, [$parameters[0], $parameters[1]]);
    }
}
