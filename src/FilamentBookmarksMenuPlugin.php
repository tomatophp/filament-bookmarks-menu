<?php

namespace TomatoPHP\FilamentBookmarksMenu;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use TomatoPHP\FilamentBookmarksMenu\Filament\Pages\Bookmarks;


class FilamentBookmarksMenuPlugin implements Plugin
{


    public function getId(): string
    {
        return 'filament-bookmarks-menu';
    }

    public function register(Panel $panel): void
    {
        $panel->pages([
            Bookmarks::class
        ]);
    }

    public function boot(Panel $panel): void
    {
        if($panel->getId() === filament()->getCurrentPanel()->getId()) {
            FilamentView::registerRenderHook(
                PanelsRenderHook::SIDEBAR_NAV_END,
                fn() => view('filament-bookmarks-menu::sidebar')
            );
        }

    }

    public static function make(): static
    {
        return new static();
    }
}
