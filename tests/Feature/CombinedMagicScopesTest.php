<?php

declare(strict_types=1);

use Workbench\App\Models\Post;

describe('CombinedMagicScopesTest', function () {

    it('generates correct SQL for chaining withUser with other scopes', function () {
        $expectedSql = Post::query()
            ->with('user')
            ->where('is_published', true)
            ->where('views', '>', 50)
            ->toRawSql();

        $actualSql = Post::withUser()
            ->published()
            ->where('views', '>', 50)
            ->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

    it('generates correct SQL for published and createdAfter', function () {
        $expectedSql = Post::query()
            ->where('is_published', true)
            ->whereDate('created_at', '>', '2024-01-01')
            ->toRawSql();

        $actualSql = Post::published()
            ->createdAfter('2024-01-01')
            ->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

    it('generates correct SQL for publishedBefore and views condition', function () {
        $expectedSql = Post::query()
            ->whereDate('published_at', '<', '2025-01-01')
            ->where('views', '>', 500)
            ->toRawSql();

        $actualSql = Post::publishedBefore('2025-01-01')
            ->whereViewsGreaterThan(500)
            ->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

    it('generates correct SQL for publishedBetween and recent', function () {
        $expectedSql = Post::query()
            ->whereBetween('published_at', ['2024-01-01', '2024-12-31'])
            ->where('published_at', '>=', now()->subDays(7))
            ->toRawSql();

        $actualSql = Post::publishedBetween('2024-01-01', '2024-12-31')
            ->recent()
            ->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

    it('generates correct SQL for published and recent and createdAfter', function () {
        $expectedSql = Post::query()
            ->where('is_published', true)
            ->where('published_at', '>=', now()->subDays(7))
            ->whereDate('created_at', '>', '2024-05-01')
            ->toRawSql();

        $actualSql = Post::published()
            ->recent()
            ->createdAfter('2024-05-01')
            ->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

    it('generates correct SQL for publishedAfter and createdBefore', function () {
        $expectedSql = Post::query()
            ->whereDate('published_at', '>', '2024-06-01')
            ->whereDate('created_at', '<', '2025-01-01')
            ->toRawSql();

        $actualSql = Post::publishedAfter('2024-06-01')
            ->createdBefore('2025-01-01')
            ->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

    
    it('generates correct SQL for combined magic scopes and recent scope', function () {
        $expectedSql = Post::query()
            ->where('is_published', true)
            ->where('views', '>', 100)
            ->where('published_at', '>=', now()->subDays(7))
            ->whereDate('created_at', '>', '2024-01-01')
            ->toRawSql();

        $actualSql = Post::query()
            ->published()
            ->where('views', '>', 100)
            ->recent()
            ->createdAfter('2024-01-01')
            ->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

});
