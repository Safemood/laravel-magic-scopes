<?php

declare(strict_types=1);

namespace Safemood\MagicScopes\Builders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Log;
use Safemood\MagicScopes\Facades\MagicScope;

class MagicScopeBuilder extends Builder
{
    public function __construct(QueryBuilder $query)
    {
        parent::__construct($query);
    }

    public function __call($method, $parameters)
    {

        Log::debug('MagicScopeBuilder __call', [
            'method' => $method,
            'parameters' => $parameters,
        ]);

        $model = $this->getModel();

        if (method_exists(Builder::class, $method)) {
            return parent::__call($method, $parameters);
        }

        if (method_exists($this->query, $method)) {
            return $this->query->$method(...$parameters);
        }

        if (MagicScope::isResolvable($method, $model)) {
            return MagicScope::resolve($this, $method, $parameters, $model);
        }

        return parent::__call($method, $parameters);
    }
}
