<?php

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use TomatoPHP\FilamentBookmarksMenu\Facades\FilamentBookmarksMenu;
use TomatoPHP\FilamentBookmarksMenu\Filament\Pages\Bookmarks;
use TomatoPHP\FilamentBookmarksMenu\FilamentBookmarksMenuPlugin;
use TomatoPHP\FilamentBookmarksMenu\Services\Contracts\BookmarkType;
use TomatoPHP\FilamentBookmarksMenu\Services\FilamentBookmarksMenuServices;

it('boots the service provider', function () {
    expect(app('filament-bookmarks-menu'))->toBeInstanceOf(FilamentBookmarksMenuServices::class)
        ->and(app('filament-bookmarks-menu'))->toBe(app('filament-bookmarks-menu'))
        ->and(Artisan::all())->toHaveKey('filament-bookmarks-menu:install')
        ->and(trans('filament-bookmarks-menu::messages.page.title'))->toBe('Bookmarks');
});

it('registers the plugin on the panel', function () {
    $panel = Filament::getPanel('admin');

    expect($panel->hasPlugin('filament-bookmarks-menu'))->toBeTrue()
        ->and($panel->getPlugin('filament-bookmarks-menu'))->toBeInstanceOf(FilamentBookmarksMenuPlugin::class)
        ->and($panel->getPages())->toContain(Bookmarks::class)
        ->and(Bookmarks::getUrl(['id' => 1]))->toEndWith('/admin/bookmarks?id=1');
});

it('keeps registered bookmark types', function () {
    FilamentBookmarksMenu::register([
        BookmarkType::make('work')->label('Work'),
        BookmarkType::make('other')->label('Other')->panel('other'),
    ]);

    expect(FilamentBookmarksMenu::load())->toHaveCount(2)
        ->and(app('filament-bookmarks-menu')->load())->toHaveCount(2);
});

it('runs the install command', function () {
    $this->artisan('filament-bookmarks-menu:install')->assertSuccessful();

    expect(Schema::hasTable('bookmarks'))->toBeTrue()
        ->and(Schema::hasTable('bookmark_links'))->toBeTrue()
        ->and(Schema::hasTable('bookmarkable'))->toBeTrue();
});
