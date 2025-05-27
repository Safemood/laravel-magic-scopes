<?php

use Workbench\App\Models\Post;

describe('ForeignKeyFieldScopeResolver', function () {

    it('generates correct SQL for forUser scope', function () {
        $expectedSql = Post::query()
            ->where('user_id', 1)
            ->toRawSql();

        $actualSql = Post::forUser(1)->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

    it('generates correct SQL for forCategories scope', function () {
        $expectedSql = Post::query()
            ->whereIn('category_id', [1, 2])
            ->toRawSql();

        $actualSql = Post::forCategory([1, 2])->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

    it('eager loads relationship with withUser', function () {
        $query = Post::withUser();
        $eagerLoads = $query->getEagerLoads();

        expect(array_key_exists('user', $eagerLoads))->toBeTrue();
    });

    it('eager loads relationship with withUsers', function () {
        $query = Post::withUsers();
        $eagerLoads = $query->getEagerLoads();

        expect(array_key_exists('users', $eagerLoads))->toBeTrue();
    });

    it('generates correct SQL for withUser() without parameters (eager load)', function () {
        $expectedSql = Post::query()->with('user')->toRawSql();
        $actualSql = Post::withUser()->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

    it('generates correct SQL for withUser(3) using whereHas', function () {
        $expectedSql = Post::query()->whereHas('user', function ($q) {
            $q->where('id', 3);
        })->toRawSql();

        $actualSql = Post::withUser(3)->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

    it('generates correct SQL for withUser([1, 2]) using whereHas with whereIn', function () {
        $expectedSql = Post::query()->whereHas('user', function ($q) {
            $q->whereIn('id', [1, 2]);
        })->toRawSql();

        $actualSql = Post::withUser([1, 2])->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

    it('generates correct SQL for withAuthor() without parameters (eager load)', function () {
        $expectedSql = Post::query()->with('author')->toRawSql();
        $actualSql = Post::withAuthor()->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

    it('generates correct SQL for withAuthor(10) using whereHas', function () {
        $expectedSql = Post::query()->whereHas('author', function ($q) {
            $q->where('id', 10);
        })->toRawSql();

        $actualSql = Post::withAuthor(10)->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

    it('generates correct SQL for withAuthor([5, 6]) using whereHas with whereIn', function () {
        $expectedSql = Post::query()->whereHas('author', function ($q) {
            $q->whereIn('id', [5, 6]);
        })->toRawSql();

        $actualSql = Post::withAuthor([5, 6])->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

    it('generates SQL containing custom editor_unformal_id condition for withEditor(3)', function () {
        $sql = Post::withEditor(3)->toRawSql();

        expect($sql)->toContain('editor_unformal_id');
        expect($sql)->toContain('3');
    });

    it('generates SQL containing custom editor_unformal_id IN condition for withEditor([7, 8])', function () {
        $sql = Post::withEditor([7, 8])->toRawSql();

        expect($sql)->toContain('editor_unformal_id');
        expect($sql)->toContain('7');
        expect($sql)->toContain('8');
    });

});
