<?php

namespace Safemood\MagicScopes\Traits;

use Safemood\MagicScopes\Facades\MagicScope;

trait HasMagicScopes
{
    /**
     * Handle dynamic method calls (instance methods).
     */
    public function __call($method, $parameters)
    {

        if (MagicScope::isResolvable($method, $this)) {
            return MagicScope::resolve($this->newQuery(), $method, $parameters, $this);
        }

        return parent::__call($method, $parameters);
    }

    /**
     * Handle static method calls.
     */
    public static function __callStatic($method, $parameters)
    {

        $instance = new static;

        if (MagicScope::isResolvable($method, $instance)) {
            return MagicScope::resolve($instance->newQuery(), $method, $parameters, $instance);
        }

        return parent::__callStatic($method, $parameters);
    }
}
