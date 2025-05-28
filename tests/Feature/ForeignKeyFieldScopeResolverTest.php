<?php

declare(strict_types=1);

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

    it('eager loads relationship with withUser()', function () {
        $query = Post::withUser();
        $eagerLoads = $query->getEagerLoads();

        expect(array_key_exists('user', $eagerLoads))->toBeTrue();
    });

    it('eager loads relationship with withUsers()', function () {
        $query = Post::withUsers();
        $eagerLoads = $query->getEagerLoads();

        expect(array_key_exists('users', $eagerLoads))->toBeTrue();
    });

    it('generates correct SQL for withUser() without parameters (eager load all)', function () {
        $expectedSql = Post::query()->with('user')->toRawSql();
        $actualSql = Post::withUser()->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

    it('eager loads only matching user models for withUser(3)', function () {
        $expectedSql = Post::query()
            ->with(['user' => fn ($q) => $q->where('id', 3)])
            ->toRawSql();

        $actualSql = Post::withUser(3)->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

    it('eager loads only matching user models for withUser([1, 2])', function () {
        $expectedSql = Post::query()
            ->with(['user' => fn ($q) => $q->whereIn('id', [1, 2])])
            ->toRawSql();

        $actualSql = Post::withUser([1, 2])->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

    it('generates correct SQL for withAuthor() without parameters (eager load all)', function () {
        $expectedSql = Post::query()->with('author')->toRawSql();
        $actualSql = Post::withAuthor()->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

    it('eager loads only matching author models for withAuthor(10)', function () {
        $expectedSql = Post::query()
            ->with(['author' => fn ($q) => $q->where('id', 10)])
            ->toRawSql();

        $actualSql = Post::withAuthor(10)->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

    it('eager loads only matching author models for withAuthor([5, 6])', function () {
        $expectedSql = Post::query()
            ->with(['author' => fn ($q) => $q->whereIn('id', [5, 6])])
            ->toRawSql();

        $actualSql = Post::withAuthor([5, 6])->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

    it('eager loads only matching editor models with custom key editor_unformal_id for withEditor(3)', function () {
        $expectedSql = Post::query()
            ->with(['editor' => fn ($q) => $q->where('editor_unformal_id', 3)])
            ->toRawSql();

        $actualSql = Post::withEditor(3)->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

    it('eager loads only matching editor models with custom key editor_unformal_id for withEditor([7, 8])', function () {
        $expectedSql = Post::query()
            ->with(['editor' => fn ($q) => $q->whereIn('editor_unformal_id', [7, 8])])
            ->toRawSql();

        $actualSql = Post::withEditor([7, 8])->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

});
