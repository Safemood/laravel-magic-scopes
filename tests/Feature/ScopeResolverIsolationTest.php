<?php

declare(strict_types=1);

use Workbench\App\Models\Post;

describe('MagicScopes package isolation', function () {

    it('does not modify queries without scope methods', function () {
        $query = Post::query()->where('title', 'Test Post');

        $sqlBefore = $query->toRawSql();

        $resultQuery = $query;

        $sqlAfter = $resultQuery->toRawSql();

        expect($sqlAfter)->toEqual($sqlBefore);
    });

    it('throws exception for calling undefined scopes or methods', function () {
        Post::fakeMethod();
    })->throws(BadMethodCallException::class);

    it('does not break queries when using standard query builder methods', function () {
        $query = Post::query()->where('id', '>', 5)->orderBy('created_at', 'desc');

        $sqlBefore = $query->toRawSql();

        $resultQuery = $query;

        $sqlAfter = $resultQuery->toRawSql();

        expect($sqlAfter)->toEqual($sqlBefore);
    });

    it('does not interfere with real scopes like recent', function () {
        $sqlBefore = Post::query()->recent()->toRawSql();
        $sqlAfter = Post::recent()->toRawSql();

        expect($sqlAfter)->toEqual($sqlBefore);
    });
});
