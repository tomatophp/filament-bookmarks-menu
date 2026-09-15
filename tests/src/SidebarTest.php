<?php

use Filament\Actions\Testing\TestAction;
use Filament\Facades\Filament;
use Livewire\Livewire;
use TomatoPHP\FilamentBookmarksMenu\Facades\FilamentBookmarksMenu;
use TomatoPHP\FilamentBookmarksMenu\Livewire\BookmarkSidebar;
use TomatoPHP\FilamentBookmarksMenu\Models\Bookmark;
use TomatoPHP\FilamentBookmarksMenu\Services\Contracts\BookmarkType;
use TomatoPHP\FilamentBookmarksMenu\Tests\Database\Factories\BookmarkFactory;
use TomatoPHP\FilamentBookmarksMenu\Tests\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('renders the user\'s bookmarks in the panel sidebar render hook', function () {
    BookmarkFactory::new()->create(['name' => 'Team folder']);
    BookmarkFactory::new()->privateFor($this->user)->create(['name' => 'My private folder']);

    $this->get(Filament::getPanel('admin')->getUrl())
        ->assertOk()
        ->assertSee('Folders')
        ->assertSee('Team folder')
        ->assertSee('My private folder');
});

it('hides other users\' private bookmarks from the sidebar', function () {
    $otherUser = User::factory()->create();
    BookmarkFactory::new()->create(['name' => 'Team folder']);
    BookmarkFactory::new()->privateFor($otherUser)->create(['name' => 'Their private folder']);

    Livewire::test(BookmarkSidebar::class)
        ->assertSuccessful()
        ->assertSee('Team folder')
        ->assertDontSee('Their private folder');

    $this->get(Filament::getPanel('admin')->getUrl())
        ->assertOk()
        ->assertDontSee('Their private folder');
});

it('creates a bookmark collection from the sidebar', function () {
    Livewire::test(BookmarkSidebar::class)
        ->callAction(TestAction::make('create')->arguments(['type' => 'folder']), data: [
            'name' => 'Reading list',
        ])
        ->assertRedirect();

    expect(Bookmark::query()->where('name', 'Reading list')->first())
        ->not->toBeNull()
        ->type->toBe('folder')
        ->is_private->toBeFalse()
        ->user_id->toEqual($this->user->id);
});

it('groups bookmarks by the registered types of the current panel', function () {
    FilamentBookmarksMenu::register([
        BookmarkType::make('clients')->label('Clients'),
        BookmarkType::make('elsewhere')->label('Elsewhere')->panel('another-panel'),
    ]);

    BookmarkFactory::new()->create(['type' => 'clients', 'name' => 'Acme']);

    Livewire::test(BookmarkSidebar::class)
        ->assertSee('Clients')
        ->assertSee('Acme')
        ->assertDontSee('Elsewhere');
});

it('escapes bookmark names in the sidebar', function () {
    BookmarkFactory::new()->create(['name' => '<img src=x onerror=alert(1)>']);

    Livewire::test(BookmarkSidebar::class)
        ->assertDontSee('<img src=x onerror=alert(1)>', escape: false);
});
