<?php

use Workbench\App\Models\Post;

describe('NumberFieldScopeResolver', function () {

    it('generates correct SQL for whereViewsGreaterThan(50)', function () {
        $expected = Post::query()->where('views', '>', 50)->toRawSql();
        $actual = Post::whereViewsGreaterThan(50)->toRawSql();

        expect($actual)->toEqual($expected);
    });

    it('generates correct SQL for whereScoreEquals(90)', function () {
        $expected = Post::query()->where('score', '=', 90)->toRawSql();
        $actual = Post::whereScoreEquals(90)->toRawSql();

        expect($actual)->toEqual($expected);
    });

    it('generates correct SQL for wherePriceBetween(100, 200)', function () {
        $expected = Post::query()->whereBetween('price', [100, 200])->toRawSql();
        $actual = Post::wherePriceBetween(100, 200)->toRawSql();

        expect($actual)->toEqual($expected);
    });

    it('generates correct SQL for whereDownloadsEquals(10)', function () {
        $expected = Post::query()->where('downloads', '=', 10)->toRawSql();
        $actual = Post::whereDownloadsEquals(10)->toRawSql();

        expect($actual)->toEqual($expected);
    });

});
