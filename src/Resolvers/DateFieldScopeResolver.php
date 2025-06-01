<?php

declare(strict_types=1);

namespace Safemood\MagicScopes\Resolvers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Safemood\MagicScopes\Contracts\ScopeResolverContract;

class DateFieldScopeResolver implements ScopeResolverContract
{
    public function matches(string $method, Model $model): bool
    {
        return (bool) preg_match('/^(?!where).*(At|Before|After|Between)$/', $method);
    }

    public function apply(Builder $query, string $method, array $parameters, Model $model): Builder
    {
        $suffix = Str::match('/^(?!where).*(At|Before|After|Between)$/', $method);
        $field = Str::snake(Str::beforeLast($method, $suffix));

        if (! Str::endsWith($field, '_at')) {
            $field .= '_at';
        }

        match ($suffix) {
            'At' => $query->whereDate($field, $parameters[0]),
            'Before' => $query->whereDate($field, '<', $parameters[0]),
            'After' => $query->whereDate($field, '>', $parameters[0]),
            'Between' => $query->whereBetween($field, $parameters),
            default => throw new \InvalidArgumentException("Unknown date scope suffix [$suffix]."),
        };

        return $query;
    }
}
