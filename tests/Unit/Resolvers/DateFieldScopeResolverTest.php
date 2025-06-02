<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Safemood\MagicScopes\Resolvers\DateFieldScopeResolver;

beforeEach(function () {
    $this->resolver = new DateFieldScopeResolver;

    $this->model = Mockery::mock(Model::class);

});

afterEach(function () {
    Mockery::close();
});

function invokeMethod(object $object, string $methodName, array $parameters = [])
{
    $reflection = new ReflectionClass($object);
    $method = $reflection->getMethod($methodName);
    $method->setAccessible(true);

    return $method->invokeArgs($object, $parameters);
}

it('matches valid date scope methods', function () {
    expect($this->resolver->matches('createdAt', $this->model))->toBeTrue();
    expect($this->resolver->matches('updatedBefore', $this->model))->toBeTrue();
    expect($this->resolver->matches('deletedAfter', $this->model))->toBeTrue();
    expect($this->resolver->matches('publishedBetween', $this->model))->toBeTrue();

    expect($this->resolver->matches('whereCreatedAt', $this->model))->toBeFalse();
    expect($this->resolver->matches('randomMethod', $this->model))->toBeFalse();
});

it('apply throws exception for unknown suffix', function () {
    $query = Mockery::mock(Builder::class);

    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('Unknown date scope suffix [].');

    $this->resolver->apply($query, 'createdAtUnknown', ['2025-05-28'], $this->model);
});

it('apply handles At suffix correctly', function () {
    $query = Mockery::mock(Builder::class);
    $query->shouldReceive('whereDate')->once()->with('created_at', '2025-05-28')->andReturnSelf();

    $result = $this->resolver->apply($query, 'createdAt', ['2025-05-28'], $this->model);

    expect($result)->toBe($query);
});

it('apply handles Before suffix correctly', function () {
    $query = Mockery::mock(Builder::class);
    $query->shouldReceive('whereDate')->once()->with('updated_at', '<', '2025-05-28')->andReturnSelf();

    $result = $this->resolver->apply($query, 'updatedBefore', ['2025-05-28'], $this->model);

    expect($result)->toBe($query);
});

it('apply handles After suffix correctly', function () {
    $query = Mockery::mock(Builder::class);
    $query->shouldReceive('whereDate')->once()->with('deleted_at', '>', '2025-05-28')->andReturnSelf();

    $result = $this->resolver->apply($query, 'deletedAfter', ['2025-05-28'], $this->model);

    expect($result)->toBe($query);
});

it('apply handles Between suffix correctly', function () {
    $query = Mockery::mock(Builder::class);
    $query->shouldReceive('whereBetween')->once()->with('published_at', ['2025-05-01', '2025-05-31'])->andReturnSelf();

    $result = $this->resolver->apply($query, 'publishedBetween', ['2025-05-01', '2025-05-31'], $this->model);

    expect($result)->toBe($query);
});
 
