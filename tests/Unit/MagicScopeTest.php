<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Safemood\MagicScopes\Facades\MagicScope as MagicScopeFacade;
use Safemood\MagicScopes\MagicScope;
use Safemood\MagicScopes\Tests\Support\FakeResolvers\FakeResolver;

beforeEach(function () {
    Config::set('magic-scopes.resolvers', []);
});

it('loads resolvers from config without mocks', function () {

    Config::set('magic-scopes.resolvers', [FakeResolver::class]);

    $magicScope = new MagicScope;

    $resolvers = getProtected($magicScope, 'resolvers');

    expect($resolvers)->toHaveCount(1);
    expect($resolvers[0])->toBeInstanceOf(FakeResolver::class);
});

it('resolves scope using real resolver instance', function () {
    $magicScope = new MagicScope;

    $model = new class extends Model
    {
        protected $table = 'users';
    };

    $query = new Builder($model->newQuery()->getQuery());

    $result = $magicScope->resolve($query, 'testScope', ['param1'], $model);

    expect($result)->toBe($query);
});

it('returns query if no resolver matches', function () {
    $magicScope = new MagicScope;

    $model = new class extends Model
    {
        protected $table = 'users';
    };

    $query = new Builder($model->newQuery()->getQuery());

    $result = $magicScope->resolve($query, 'nonExistentScope', [], $model);

    expect($result)->toBe($query);
});

it('can add a resolver manually and use it', function () {
    $magicScope = new MagicScope;

    $customResolver = new FakeResolver;
    $magicScope->addResolver($customResolver);

    $model = new class extends Model
    {
        protected $table = 'users';
    };

    $query = new Builder($model->newQuery()->getQuery());

    $result = $magicScope->resolve($query, 'testScope', [], $model);

    expect($result)->toBe($query);
});

it('throws exception if multiple resolvers match (ambiguous resolution)', function () {
    $magicScope = new MagicScope;

    $resolver1 = new FakeResolver;
    $resolver2 = new FakeResolver;

    $magicScope->addResolver($resolver1);
    $magicScope->addResolver($resolver2);

    $model = new class extends Model
    {
        protected $table = 'users';
    };

    $query = new Builder($model->newQuery()->getQuery());

    $this->expectException(RuntimeException::class);
    $this->expectExceptionMessage('Multiple scope resolvers matched method [testScope]');

    $magicScope->resolve($query, 'testScope', [], $model);
});

it('can add resolver and resolve scopes via facade', function () {
    MagicScopeFacade::addResolver(new FakeResolver);

    $model = new class extends Model
    {
        protected $table = 'users';
    };

    $query = new Builder($model->newQuery()->getQuery());

    $result = MagicScopeFacade::resolve($query, 'testScope', [], $model);

    expect($result)->toBe($query);
});

function getProtected(object $object, string $property)
{
    $reflection = new ReflectionClass($object);
    $prop = $reflection->getProperty($property);
    $prop->setAccessible(true);

    return $prop->getValue($object);
}
