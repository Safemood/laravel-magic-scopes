<?php

namespace Safemood\MagicScopes\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Safemood\MagicScopes\MagicScope
 */
class MagicScope extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Safemood\MagicScopes\MagicScope::class;
    }
}
