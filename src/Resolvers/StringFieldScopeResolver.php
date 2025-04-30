<?php

namespace Safemood\MagicScopes\Resolvers;

use Illuminate\Database\Eloquent\Builder;
use Safemood\MagicScopes\Contracts\ScopeResolverContract;
use Illuminate\Support\Str;

class StringFieldScopeResolver implements ScopeResolverContract
{
    protected string $positivePrefix = 'has';
    protected string $negativePrefix = 'hasNot';

    public function matches(string $method, $model): bool
    {
        return $this->isPositiveCondition($method) || $this->isNegativeCondition($method);
    }

    public function apply(Builder $query, string $method, array $parameters, $model): Builder
    {
        $field = $this->extractFieldName($method);
        $value = $parameters[0];

        if ($this->isNegativeCondition($method)) {
            return $this->applyNegation($query, $method, $field, $value);
        }

        return $this->applyCondition($query, $method, $field, $value);
    }

    protected function applyCondition(Builder $query, string $method, string $field, string $value): Builder
    {
        if ($this->isLikeCondition($method)) {
            return $query->where($field, 'LIKE', "%{$value}%");
        }

        if ($this->isStartWithCondition($method)) {
            return $query->where($field, 'LIKE', "{$value}%");
        }

        if ($this->isEndWithCondition($method)) {
            return $query->where($field, 'LIKE', "%{$value}");
        }

        return $query->where($field, '=', $value);
    }

    protected function applyNegation(Builder $query, string $method, string $field, string $value): Builder
    {
        if ($this->isLikeCondition($method)) {
            return $query->where($field, 'NOT LIKE', "%{$value}%");
        }

        if ($this->isStartWithCondition($method)) {
            return $query->where($field, 'NOT LIKE', "{$value}%");
        }

        if ($this->isEndWithCondition($method)) {
            return $query->where($field, 'NOT LIKE', "%{$value}");
        }

        return $query->where($field, '<>', $value);
    }

    protected function isPositiveCondition(string $method): bool
    {
        return Str::startsWith($method, $this->positivePrefix);
    }

    protected function isNegativeCondition(string $method): bool
    {
        return Str::startsWith($method, $this->negativePrefix);
    }

    protected function isLikeCondition(string $method): bool
    {
        return Str::endsWith($method, 'Like');
    }

    protected function isStartWithCondition(string $method): bool
    {
        return Str::endsWith($method, 'StartWith');
    }

    protected function isEndWithCondition(string $method): bool
    {
        return Str::endsWith($method, 'EndWith');
    }

    protected function extractFieldName(string $method): string
    {
        $prefixLength = $this->isNegativeCondition($method)
            ? strlen($this->negativePrefix)
            : strlen($this->positivePrefix);

        $field = substr($method, $prefixLength);

        foreach (['Like', 'StartWith', 'EndWith'] as $suffix) {
            if (Str::endsWith($field, $suffix)) {
                $field = Str::beforeLast($field, $suffix);
                break;
            }
        }

        return Str::snake($field);
    }
}
