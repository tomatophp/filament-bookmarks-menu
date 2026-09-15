<?php

use Filament\Actions\Testing\TestAction;
use Livewire\Livewire;
use TomatoPHP\FilamentBookmarksMenu\Models\BookmarkLink;
use TomatoPHP\FilamentBookmarksMenu\Tests\Database\Factories\BookmarkFactory;
use TomatoPHP\FilamentBookmarksMenu\Tests\Models\User;
use TomatoPHP\FilamentBookmarksMenu\Tests\Resources\UserResource;
use TomatoPHP\FilamentBookmarksMenu\Tests\Resources\UserResource\Pages\EditUser;
use TomatoPHP\FilamentBookmarksMenu\Tests\Resources\UserResource\Pages\ListUsers;

beforeEach(function () {
    $this->user = User::factory()->create(['name' => 'Alice']);
    $this->actingAs($this->user);
});

it('renders resource pages that use the bookmark actions', function () {
    Livewire::test(ListUsers::class)->assertSuccessful();
    Livewire::test(EditUser::class, ['record' => $this->user->getKey()])->assertSuccessful();
});

it('bookmarks and un-bookmarks a record from the table', function () {
    $folder = BookmarkFactory::new()->create();
    $url = UserResource::getUrl('edit', ['record' => $this->user]);

    Livewire::test(ListUsers::class)
        ->callAction(TestAction::make('table_bookmark')->table($this->user), data: [
            'bookmark_id' => [$folder->id],
        ])
        ->assertHasNoFormErrors();

    $link = BookmarkLink::query()->where('url', $url)->first();

    expect($link)->not->toBeNull()
        ->name->toBe('Alice')
        ->icon->toBe('heroicon-o-users')
        ->and($link->bookmarks()->pluck('bookmarks.id')->all())->toBe([$folder->id]);

    Livewire::test(ListUsers::class)
        ->callAction(TestAction::make('table_bookmark')->table($this->user));

    expect(BookmarkLink::query()->where('url', $url)->exists())->toBeFalse();
});

it('bookmarks the current page from a header action', function () {
    $folder = BookmarkFactory::new()->create();

    Livewire::test(EditUser::class, ['record' => $this->user->getKey()])
        ->callAction(TestAction::make('bookmark'), data: [
            'bookmark_id' => [$folder->id],
        ])
        ->assertHasNoFormErrors();

    expect($folder->links()->pluck('url')->all())
        ->toBe([EditUser::getUrl(['record' => $this->user])]);
});

it('bulk bookmarks and clears records', function () {
    $folder = BookmarkFactory::new()->create();
    $bob = User::factory()->create();

    Livewire::test(ListUsers::class)
        ->selectTableRecords([$this->user->getKey(), $bob->getKey()])
        ->callAction(TestAction::make('bulk_bookmark')->table()->bulk(), data: [
            'bookmark_id' => [$folder->id],
        ])
        ->assertHasNoFormErrors();

    expect($folder->links()->count())->toBe(2);

    Livewire::test(ListUsers::class)
        ->selectTableRecords([$this->user->getKey(), $bob->getKey()])
        ->callAction(TestAction::make('bulk_clear_bookmark')->table()->bulk());

    expect(BookmarkLink::query()->count())->toBe(0);
});

it('does not let a user attach to or remove from another user\'s private bookmark', function () {
    $otherUser = User::factory()->create();
    $theirFolder = BookmarkFactory::new()->privateFor($otherUser)->create();
    $url = UserResource::getUrl('edit', ['record' => $this->user]);

    $theirLink = BookmarkLink::saveToBookmarks($url, ['name' => 'Alice'], [$theirFolder->id], $otherUser);

    // The record is not bookmarked as far as the current user can see ...
    expect(BookmarkLink::findVisibleByUrl($url, $this->user))->toBeNull();

    // ... the other user's private folder is not a valid choice ...
    $myFolder = BookmarkFactory::new()->create();

    Livewire::test(ListUsers::class)
        ->callAction(TestAction::make('table_bookmark')->table($this->user), data: [
            'bookmark_id' => [$myFolder->id, $theirFolder->id],
        ])
        ->assertHasActionErrors();

    expect($theirLink->bookmarks()->pluck('bookmarks.id')->all())->toBe([$theirFolder->id]);

    // ... and saving into the user's own folder reuses the link without touching the other user's bookmark.
    Livewire::test(ListUsers::class)
        ->callAction(TestAction::make('table_bookmark')->table($this->user), data: [
            'bookmark_id' => [$myFolder->id],
        ])
        ->assertHasNoActionErrors();

    expect($theirLink->bookmarks()->pluck('bookmarks.id')->sort()->values()->all())
        ->toBe([$theirFolder->id, $myFolder->id]);

    // Removing it only detaches it from the bookmarks the current user can see.
    Livewire::test(ListUsers::class)
        ->callAction(TestAction::make('table_bookmark')->table($this->user));

    expect($theirLink->fresh())->not->toBeNull()
        ->and($theirLink->bookmarks()->pluck('bookmarks.id')->all())->toBe([$theirFolder->id]);
});
