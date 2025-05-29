<?php

declare(strict_types=1);

namespace Safemood\MagicScopes\Tests\Support\FakeResolvers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Safemood\MagicScopes\Contracts\ScopeResolverContract;

class FakeResolver implements ScopeResolverContract
{
    public function matches(string $method, Model $model): bool
    {
        return $method === 'testScope';
    }

    public function apply(Builder $query, string $method, array $parameters, Model $model): Builder
    {
        return $query;
    }
}
