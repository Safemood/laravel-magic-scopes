<?php

declare(strict_types=1);

use Workbench\App\Models\Post;

describe('BooleanFieldScopeResolver', function () {

    it('generates correct SQL for published scope', function () {
        $expectedSql = Post::query()->where('is_published', true)->toRawSql();
        $actualSql = Post::published()->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

    it('generates correct SQL for notPublished scope', function () {
        $expectedSql = Post::query()->where('is_published', false)->toRawSql();
        $actualSql = Post::notPublished()->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

    it('generates correct SQL for hasFeaturedImage scope', function () {
        $expectedSql = Post::query()->where('has_featured_image', true)->toRawSql();
        $actualSql = Post::hasFeaturedImage()->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

    it('generates correct SQL for hasNotFeaturedImage scope', function () {
        $expectedSql = Post::query()->where('has_featured_image', false)->toRawSql();
        $actualSql = Post::hasNotFeaturedImage()->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

    it('generates correct SQL for sticky scope', function () {
        $expectedSql = Post::query()->where('is_sticky', true)->toRawSql();
        $actualSql = Post::sticky()->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

    it('generates correct SQL for notSticky scope', function () {
        $expectedSql = Post::query()->where('is_sticky', false)->toRawSql();
        $actualSql = Post::notSticky()->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

    it('generates correct SQL for reviewed scope', function () {
        $expectedSql = Post::query()->where('is_reviewed', true)->toRawSql();
        $actualSql = Post::reviewed()->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

    it('generates correct SQL for notReviewed scope', function () {
        $expectedSql = Post::query()->where('is_reviewed', false)->toRawSql();
        $actualSql = Post::notReviewed()->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });
});
