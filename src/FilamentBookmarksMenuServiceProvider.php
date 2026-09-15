<?php

namespace TomatoPHP\FilamentBookmarksMenu;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use TomatoPHP\FilamentBookmarksMenu\Components\BookmarkGroup;
use TomatoPHP\FilamentBookmarksMenu\Components\BookmarkItem;
use TomatoPHP\FilamentBookmarksMenu\Console\FilamentBookmarksMenuInstall;
use TomatoPHP\FilamentBookmarksMenu\Livewire\BookmarkSidebar;
use TomatoPHP\FilamentBookmarksMenu\Services\FilamentBookmarksMenuServices;

class FilamentBookmarksMenuServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('filament-bookmarks-menu', fn (): FilamentBookmarksMenuServices => new FilamentBookmarksMenuServices);

        $this->mergeConfigFrom(__DIR__.'/../config/filament-bookmarks-menu.php', 'filament-bookmarks-menu');
    }

    public function boot(): void
    {
        $this->commands([
            FilamentBookmarksMenuInstall::class,
        ]);

        $this->publishes([
            __DIR__.'/../config/filament-bookmarks-menu.php' => config_path('filament-bookmarks-menu.php'),
        ], 'filament-bookmarks-menu-config');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'filament-bookmarks-menu-migrations');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'filament-bookmarks-menu');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/filament-bookmarks-menu'),
        ], 'filament-bookmarks-menu-views');

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'filament-bookmarks-menu');

        $this->publishes([
            __DIR__.'/../resources/lang' => base_path('lang/vendor/filament-bookmarks-menu'),
        ], 'filament-bookmarks-menu-lang');

        Livewire::component('bookmark-sidebar', BookmarkSidebar::class);

        $this->loadViewComponentsAs('filament', [
            BookmarkItem::class,
            BookmarkGroup::class,
        ]);
    }
}
