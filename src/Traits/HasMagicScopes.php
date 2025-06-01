<?php

declare(strict_types=1);

namespace Safemood\MagicScopes\Traits;

use Illuminate\Database\Eloquent\Builder;
use Safemood\MagicScopes\Builders\MagicScopeBuilder;

trait HasMagicScopes
{
    public function newEloquentBuilder($query): Builder
    {
        return new MagicScopeBuilder($query);
    }
}
