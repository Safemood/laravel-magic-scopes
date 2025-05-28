<?php

declare(strict_types=1);

namespace Safemood\MagicScopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Safemood\MagicScopes\Contracts\ScopeResolverContract;

class MagicScope
{
    /**
     * List of scope resolvers.
     *
     * @var ScopeResolverContract[]
     */
    protected $resolvers = [];

    /**
     * Register default scope resolvers.
     */
    public function __construct()
    {
        $this->loadResolvers();
    }

    /**
     * Add a custom scope resolver.
     */
    public function addResolver(ScopeResolverContract $resolver): void
    {
        $this->resolvers[] = $resolver;
    }

    /**
     * Load scope resolvers from configuration.
     */
    protected function loadResolvers(): void
    {
        $resolverClasses = Config::get('magic-scopes.resolvers', []);

        foreach ($resolverClasses as $resolverClass) {
            $this->resolvers[] = App::make($resolverClass);
        }
    }

    /**
     * Handle the dynamic scope call.
     */
    public function resolve(Builder $query, string $method, array $parameters, Model $model): Builder
    {
        $matchedResolvers = array_filter($this->resolvers, fn ($resolver) => $resolver->matches($method, $model));

        $count = count($matchedResolvers);

        if ($count === 0) {
            return $query;
        }

        if ($count > 1) {
            $resolverClasses = array_map(fn ($resolver) => get_class($resolver), $matchedResolvers);
            $resolverList = implode(', ', $resolverClasses);
            throw new \RuntimeException("Multiple scope resolvers matched method [$method]: [$resolverList]. Ambiguous resolution.");
        }

        $resolver = array_values($matchedResolvers)[0];

        return $resolver->apply($query, $method, $parameters, $model);
    }

    /**
     * Check if a method is resolvable.
     */
    public function isResolvable(string $method, Model $model): bool
    {
        foreach ($this->resolvers as $resolver) {
            if ($resolver->matches($method, $model)) {
                return true;
            }
        }

        return false;
    }
}
