<?php

use TomatoPHP\FilamentBookmarksMenu\Models\Bookmark;
use TomatoPHP\FilamentBookmarksMenu\Tests\Database\Factories\BookmarkFactory;
use TomatoPHP\FilamentBookmarksMenu\Tests\Models\User;

it('scopes private bookmarks to their owner', function () {
    $alice = User::factory()->create();
    $bob = User::factory()->create();

    $public = BookmarkFactory::new()->create();
    $alicePrivate = BookmarkFactory::new()->privateFor($alice)->create();
    $bobPrivate = BookmarkFactory::new()->privateFor($bob)->create();

    expect(Bookmark::query()->visibleTo($alice)->pluck('id')->sort()->values()->all())
        ->toBe([$public->id, $alicePrivate->id])
        ->and(Bookmark::query()->visibleTo($bob)->pluck('id')->sort()->values()->all())
        ->toBe([$public->id, $bobPrivate->id])
        ->and(Bookmark::query()->visibleTo(null)->pluck('id')->all())
        ->toBe([$public->id])
        ->and($bobPrivate->isVisibleTo($alice))->toBeFalse()
        ->and($bobPrivate->isVisibleTo($bob))->toBeTrue()
        ->and($public->isVisibleTo(null))->toBeTrue();
});

it('keeps the type filter when combined with the visibility scope', function () {
    $alice = User::factory()->create();

    BookmarkFactory::new()->create(['type' => 'folder']);
    $clients = BookmarkFactory::new()->privateFor($alice)->create(['type' => 'clients']);

    expect(Bookmark::query()->where('type', 'clients')->visibleTo($alice)->pluck('id')->all())
        ->toBe([$clients->id]);
});
