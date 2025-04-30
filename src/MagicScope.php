<?php

namespace Safemood\MagicScopes;

use Illuminate\Database\Eloquent\Builder;
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
     * Prefix for scope methods.
     */
    protected ?string $prefix;

    /**
     * Register default scope resolvers.
     */
    public function __construct()
    {
        $this->prefix = Config::get('magic-scopes.scope_prefix'); // Default prefix from config
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
     *
     * @param  mixed  $model
     */
    public function resolve(Builder $query, string $method, array $parameters, $model): Builder
    {
        $prefixedMethod = $this->applyPrefix($method);

        foreach ($this->resolvers as $resolver) {
            if ($resolver->matches($prefixedMethod, $model)) {
                return $resolver->apply($query, $prefixedMethod, $parameters, $model);
            }
        }

        return $query;
    }

    /**
     * Check if a method is resolvable.
     *
     * @param  mixed  $model
     */
    public function isResolvable(string $method, $model): bool
    {
        $prefixedMethod = $this->applyPrefix($method);

        foreach ($this->resolvers as $resolver) {

            if ($resolver->matches($prefixedMethod, $model)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Apply the configured prefix to a method name if it's set.
     */
    protected function applyPrefix(string $method): string
    {
        return $this->prefix ? $this->prefix.ucfirst($method) : $method;
    }
}
