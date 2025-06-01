<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Safemood\MagicScopes\Resolvers\BooleanFieldScopeResolver;

beforeEach(function () {
    $this->resolver = new BooleanFieldScopeResolver;

    $this->model = Mockery::mock(Model::class);
    $this->model->shouldReceive('getFillable')
        ->andReturn(['is_active', 'has_access', 'other_field']);
});

afterEach(function () {
    Mockery::close();
});

it('matches positive boolean scopes correctly', function () {
    expect($this->resolver->matches('active', $this->model))->toBeTrue();
    expect($this->resolver->matches('hasAccess', $this->model))->toBeTrue();
    expect($this->resolver->matches('otherField', $this->model))->toBeFalse();
});

it('matches negative boolean scopes correctly', function () {
    expect($this->resolver->matches('notActive', $this->model))->toBeTrue();
    expect($this->resolver->matches('hasNotAccess', $this->model))->toBeTrue();
    expect($this->resolver->matches('notOtherField', $this->model))->toBeFalse();
});

it('returns false if no matching scope found', function () {
    expect($this->resolver->matches('nonExistentScope', $this->model))->toBeFalse();
});

it('applies positive scope with true value', function () {
    $query = Mockery::mock(Builder::class);
    $innerQuery = Mockery::mock();
    $innerQuery->wheres = [];

    $query->shouldReceive('getQuery')->andReturn($innerQuery);
    $query->shouldReceive('where')->andReturnUsing(function ($field, $value) use ($innerQuery, $query) {
        $innerQuery->wheres[] = ['column' => $field, 'value' => $value];

        return $query;
    });

    $result = $this->resolver->apply($query, 'active', [], $this->model);

    $wheres = $result->getQuery()->wheres;

    expect(collect($wheres)->contains(fn ($where) => $where['column'] === 'is_active' && $where['value'] === true))->toBeTrue();
});

it('applies negative scope with false value', function () {
    $query = Mockery::mock(Builder::class);
    $innerQuery = Mockery::mock();
    $innerQuery->wheres = [];

    $query->shouldReceive('getQuery')->andReturn($innerQuery);
    $query->shouldReceive('where')->andReturnUsing(function ($field, $value) use ($innerQuery, $query) {
        $innerQuery->wheres[] = ['column' => $field, 'value' => $value];

        return $query;
    });

    $result = $this->resolver->apply($query, 'notActive', [], $this->model);

    $wheres = $result->getQuery()->wheres;

    expect(collect($wheres)->contains(fn ($where) => $where['column'] === 'is_active' && $where['value'] === false))->toBeTrue();
});

it('applies hasNot scope with false value', function () {
    $query = Mockery::mock(Builder::class);
    $innerQuery = Mockery::mock();
    $innerQuery->wheres = [];

    $query->shouldReceive('getQuery')->andReturn($innerQuery);
    $query->shouldReceive('where')->andReturnUsing(function ($field, $value) use ($innerQuery, $query) {
        $innerQuery->wheres[] = ['column' => $field, 'value' => $value];

        return $query;
    });

    $result = $this->resolver->apply($query, 'hasNotAccess', [], $this->model);

    $wheres = $result->getQuery()->wheres;

    expect(collect($wheres)->contains(fn ($where) => $where['column'] === 'has_access' && $where['value'] === false))->toBeTrue();
});

it('getPositiveScopeName returns expected positive scopes', function () {
    expect(invokeMethod($this->resolver, 'getPositiveScopeName', ['is_active']))->toBe('active');
    expect(invokeMethod($this->resolver, 'getPositiveScopeName', ['has_access']))->toBe('hasAccess');
    expect(invokeMethod($this->resolver, 'getPositiveScopeName', ['other_field']))->toBe('');
});

it('getNegativeScopeName returns expected negative scopes', function () {
    expect(invokeMethod($this->resolver, 'getNegativeScopeName', ['is_active']))->toBe('notActive');
    expect(invokeMethod($this->resolver, 'getNegativeScopeName', ['has_access']))->toBe('hasNotAccess');
    expect(invokeMethod($this->resolver, 'getNegativeScopeName', ['other_field']))->toBe('');
});
