<?php

declare(strict_types=1);

use Workbench\App\Models\Post;

describe('JsonFieldScopeResolver', function () {

    it('generates correct SQL for rentRequestsContains scope', function () {
        $expectedSql = Post::query()
            ->whereJsonContains('rent_requests->rooms_count', 2)
            ->toRawSql();

        $actualSql = Post::rentRequestsContains('rooms_count', 2)->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

    it('generates correct SQL for rentRequestsDoesntContain scope', function () {
        $expectedSql = Post::query()
            ->whereJsonDoesntContain('rent_requests->rooms_count', 2)
            ->toRawSql();

        $actualSql = Post::rentRequestsDoesntContain('rooms_count', 2)->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

    it('generates correct SQL for rentContains scope', function () {
        $expectedSql = Post::query()
            ->whereJsonContains('rent->city', 'Paris')
            ->toRawSql();

        $actualSql = Post::rentContains('city', 'Paris')->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

    it('generates correct SQL for rentDoesntContain scope', function () {
        $expectedSql = Post::query()
            ->whereJsonDoesntContain('rent->city', 'Paris')
            ->toRawSql();

        $actualSql = Post::rentDoesntContain('city', 'Paris')->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

    it('generates correct SQL for settingsContains scope', function () {
        $expectedSql = Post::query()
            ->whereJsonContains('settings->timezone', 'UTC')
            ->toRawSql();

        $actualSql = Post::settingsContains('timezone', 'UTC')->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });

    it('generates correct SQL for settingsDoesntContain scope', function () {
        $expectedSql = Post::query()
            ->whereJsonDoesntContain('settings->timezone', 'UTC')
            ->toRawSql();

        $actualSql = Post::settingsDoesntContain('timezone', 'UTC')->toRawSql();

        expect($actualSql)->toEqual($expectedSql);
    });
});
