<?php

namespace TomatoPHP\FilamentBookmarksMenu;

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use TomatoPHP\FilamentBookmarksMenu\Services\FilamentBookmarksMenuServices;


class FilamentBookmarksMenuServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //Register generate command
        $this->commands([
           \TomatoPHP\FilamentBookmarksMenu\Console\FilamentBookmarksMenuInstall::class,
        ]);

        //Register Config file
        $this->mergeConfigFrom(__DIR__.'/../config/filament-bookmarks-menu.php', 'filament-bookmarks-menu');

        //Publish Config
        $this->publishes([
           __DIR__.'/../config/filament-bookmarks-menu.php' => config_path('filament-bookmarks-menu.php'),
        ], 'filament-bookmarks-menu-config');

        //Register Migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        //Publish Migrations
        $this->publishes([
           __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'filament-bookmarks-menu-migrations');
        //Register views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'filament-bookmarks-menu');

        //Publish Views
        $this->publishes([
           __DIR__.'/../resources/views' => resource_path('views/vendor/filament-bookmarks-menu'),
        ], 'filament-bookmarks-menu-views');

        //Register Langs
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'filament-bookmarks-menu');

        //Publish Lang
        $this->publishes([
           __DIR__.'/../resources/lang' => base_path('lang/vendor/filament-bookmarks-menu'),
        ], 'filament-bookmarks-menu-lang');

        //Register Routes
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        Livewire::component('bookmark-sidebar', \TomatoPHP\FilamentBookmarksMenu\Livewire\BookmarkSidebar::class);

        $this->loadViewComponentsAs('filament', [
            \TomatoPHP\FilamentBookmarksMenu\Components\BookmarkItem::class,
            \TomatoPHP\FilamentBookmarksMenu\Components\BookmarkGroup::class,
        ]);

    }

    public function boot(): void
    {
        $this->app->bind('filament-bookmarks-menu', function () {
            return new FilamentBookmarksMenuServices();
        });
    }
}
