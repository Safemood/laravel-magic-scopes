<?php

use Workbench\App\Models\Post;

describe('DateFieldScopeResolver', function () {

    it('generates correct SQL for publishedAt scope', function () {
        $expectedSql = Post::query()
            ->whereDate('published_at', '2024-05-10')
            ->toRawSql();

        $actualSql = Post::publishedAt('2024-05-10')->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

    it('generates correct SQL for publishedBefore scope', function () {
        $expectedSql = Post::query()
            ->whereDate('published_at', '<', '2024-05-15')
            ->toRawSql();

        $actualSql = Post::publishedBefore('2024-05-15')->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

    it('generates correct SQL for publishedAfter scope', function () {
        $expectedSql = Post::query()
            ->whereDate('published_at', '>', '2024-05-15')
            ->toRawSql();

        $actualSql = Post::publishedAfter('2024-05-15')->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

    it('generates correct SQL for publishedBetween scope', function () {
        $expectedSql = Post::query()
            ->whereBetween('published_at', ['2024-05-10', '2024-05-20'])
            ->toRawSql();

        $actualSql = Post::publishedBetween('2024-05-10', '2024-05-20')->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

    it('generates correct SQL for reviewedAt scope', function () {
        $expectedSql = Post::query()
            ->whereDate('reviewed_at', '2024-05-15')
            ->toRawSql();

        $actualSql = Post::reviewedAt('2024-05-15')->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

    it('generates correct SQL for reviewedBefore scope', function () {
        $expectedSql = Post::query()
            ->whereDate('reviewed_at', '<', '2024-05-20')
            ->toRawSql();

        $actualSql = Post::reviewedBefore('2024-05-20')->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

    it('generates correct SQL for reviewedAfter scope', function () {
        $expectedSql = Post::query()
            ->whereDate('reviewed_at', '>', '2024-05-20')
            ->toRawSql();

        $actualSql = Post::reviewedAfter('2024-05-20')->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

    it('generates correct SQL for reviewedBetween scope', function () {
        $expectedSql = Post::query()
            ->whereBetween('reviewed_at', ['2024-05-14', '2024-05-16'])
            ->toRawSql();

        $actualSql = Post::reviewedBetween('2024-05-14', '2024-05-16')->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

    it('throws exception when between is missing parameters', function () {
        expect(fn () => Post::publishedBetween('2024-05-10'))->toThrow(\InvalidArgumentException::class);
    });

    it('throws exception when no parameters are provided', function () {
        expect(fn () => Post::publishedAt())->toThrow(\InvalidArgumentException::class);
    });
});
