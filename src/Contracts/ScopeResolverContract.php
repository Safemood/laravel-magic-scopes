<?php

namespace Safemood\MagicScopes\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface ScopeResolverContract
{
    public function matches(string $method, $model): bool;

    public function apply(Builder $query, string $method, array $parameters, $model): Builder;
}
