<?php

use Filament\Actions\Testing\TestAction;
use Livewire\Livewire;
use TomatoPHP\FilamentBookmarksMenu\Filament\Pages\Bookmarks;
use TomatoPHP\FilamentBookmarksMenu\Models\Bookmark;
use TomatoPHP\FilamentBookmarksMenu\Tests\Database\Factories\BookmarkFactory;
use TomatoPHP\FilamentBookmarksMenu\Tests\Database\Factories\BookmarkLinkFactory;
use TomatoPHP\FilamentBookmarksMenu\Tests\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('renders the bookmarks page with its links', function () {
    $bookmark = BookmarkFactory::new()->create(['name' => 'Work']);
    $link = BookmarkLinkFactory::new()->create(['name' => 'Quarterly report']);
    $link->bookmarks()->attach($bookmark);

    Livewire::withQueryParams(['id' => $bookmark->id])
        ->test(Bookmarks::class)
        ->assertSuccessful()
        ->assertSee('Work')
        ->assertCanSeeTableRecords([$link]);
});

it('renders the bookmarks page over http', function () {
    $bookmark = BookmarkFactory::new()->create(['name' => 'Work']);

    $this->get(Bookmarks::getUrl(['id' => $bookmark->id]))
        ->assertOk()
        ->assertSee('Work');
});

it('redirects to the first visible bookmark when opened without an id', function () {
    $bookmark = BookmarkFactory::new()->create(['name' => 'Work']);
    BookmarkFactory::new()->create(['name' => 'Later']);

    $this->get(Bookmarks::getUrl())->assertRedirect(Bookmarks::getUrl(['id' => $bookmark->id]));
});

it('redirects to the panel home when opened without an id and there are no bookmarks', function () {
    $this->get(Bookmarks::getUrl())->assertRedirect(filament()->getPanel('admin')->getUrl());
});

it('returns 404 for an unknown bookmark id', function () {
    $this->get(Bookmarks::getUrl(['id' => 999]))->assertNotFound();
});

it('edits a bookmark', function () {
    $bookmark = BookmarkFactory::new()->create(['name' => 'Work']);

    Livewire::withQueryParams(['id' => $bookmark->id])
        ->test(Bookmarks::class)
        ->callAction(TestAction::make('edit'), data: [
            'name' => 'Clients',
            'icon' => 'heroicon-s-star',
            'color' => '#ff0000',
            'is_private' => true,
        ])
        ->assertHasNoFormErrors();

    expect($bookmark->refresh())
        ->name->toBe('Clients')
        ->icon->toBe('heroicon-s-star')
        ->color->toBe('#ff0000')
        ->is_private->toBeTrue()
        ->user_type->toBe(User::class)
        ->user_id->toEqual($this->user->id);
});

it('deletes a bookmark and the links only it holds', function () {
    $bookmark = BookmarkFactory::new()->create();
    $other = BookmarkFactory::new()->create();
    $onlyHere = BookmarkLinkFactory::new()->create();
    $shared = BookmarkLinkFactory::new()->create();
    $onlyHere->bookmarks()->attach($bookmark);
    $shared->bookmarks()->attach([$bookmark->id, $other->id]);

    Livewire::withQueryParams(['id' => $bookmark->id])
        ->test(Bookmarks::class)
        ->callAction(TestAction::make('delete'));

    expect(Bookmark::query()->find($bookmark->id))->toBeNull()
        ->and($onlyHere->fresh())->toBeNull()
        ->and($shared->fresh())->not->toBeNull()
        ->and($shared->bookmarks()->pluck('bookmarks.id')->all())->toBe([$other->id]);
});

it('removes a link from the bookmark', function () {
    $bookmark = BookmarkFactory::new()->create();
    $link = BookmarkLinkFactory::new()->create();
    $link->bookmarks()->attach($bookmark);

    Livewire::withQueryParams(['id' => $bookmark->id])
        ->test(Bookmarks::class)
        ->callAction(TestAction::make('remove')->table($link));

    expect($link->fresh())->toBeNull();
});

it('does not let a user open another user\'s private bookmark', function () {
    $otherUser = User::factory()->create();
    $private = BookmarkFactory::new()->privateFor($otherUser)->create(['name' => 'Secret stuff']);

    $this->get(Bookmarks::getUrl(['id' => $private->id]))->assertNotFound();

    $this->actingAs($otherUser)
        ->get(Bookmarks::getUrl(['id' => $private->id]))
        ->assertOk()
        ->assertSee('Secret stuff');
});
