<?php

declare(strict_types=1);

namespace Safemood\MagicScopes\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface ScopeResolverContract
{
    /**
     * Determine if the resolver matches the given method name on the model.
     */
    public function matches(string $method, Model $model): bool;

    /**
     * Apply the scope to the query builder.
     *
     * @param  array<mixed>  $parameters
     */
    public function apply(Builder $query, string $method, array $parameters, Model $model): Builder;
}
