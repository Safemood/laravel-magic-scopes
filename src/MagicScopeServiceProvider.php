<?php

declare(strict_types=1);

namespace Safemood\MagicScopes;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MagicScopeServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-magic-scopes')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {

        $this->app->singleton(MagicScope::class, function () {
            return new MagicScope;
        });
    }
}
