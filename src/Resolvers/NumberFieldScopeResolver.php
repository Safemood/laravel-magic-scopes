<?php

declare(strict_types=1);

namespace Safemood\MagicScopes\Resolvers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Safemood\MagicScopes\Contracts\ScopeResolverContract;

class NumberFieldScopeResolver implements ScopeResolverContract
{
    protected array $supportedOperators = [
        'Equals',
        'GreaterThan',
        'LessThan',
        'Between',
    ];

    public function matches(string $method, Model $model): bool
    {
        if (! Str::startsWith($method, 'where')) {
            return false;
        }

        return collect($this->supportedOperators)
            ->contains(fn ($operator) => Str::endsWith($method, $operator));
    }

    public function apply(Builder $query, string $method, array $parameters, Model $model): Builder
    {
        $methodWithoutWhere = Str::after($method, 'where');

        $operator = $this->detectOperator($methodWithoutWhere);

        if (! $operator) {
            return $query;
        }

        $field = $this->extractField($methodWithoutWhere, $operator);

        return match ($operator) {
            'Equals' => $query->where($field, '=', $parameters[0]),
            'GreaterThan' => $query->where($field, '>', $parameters[0]),
            'LessThan' => $query->where($field, '<', $parameters[0]),
            'Between' => $query->whereBetween($field, [$parameters[0], $parameters[1]]),
            default => $query,
        };
    }

    protected function detectOperator(string $method): ?string
    {
        return collect($this->supportedOperators)
            ->first(fn ($operator) => Str::endsWith($method, $operator));
    }

    protected function extractField(string $method, string $operator): string
    {
        $fieldPart = Str::beforeLast($method, $operator);

        return Str::snake($fieldPart);
    }
}
