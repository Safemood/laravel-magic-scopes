<?php

use Workbench\App\Models\Post;

describe('EnumFieldScopeResolver', function () {
    it('generates correct SQL for statusIs', function () {
        $sql = Post::statusIs('published')->toRawSql();
        $expected = Post::where('status', 'published')->toRawSql();

        expect($sql)->toEqual($expected);
    });

    it('generates correct SQL for typeIs', function () {
        $sql = Post::typeIs('announcement')->toRawSql();
        $expected = Post::where('type', 'announcement')->toRawSql();

        expect($sql)->toEqual($expected);
    });
});
