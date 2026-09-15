<?php

namespace TomatoPHP\FilamentBookmarksMenu;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use TomatoPHP\FilamentBookmarksMenu\Filament\Pages\Bookmarks;

class FilamentBookmarksMenuPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-bookmarks-menu';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->pages([
                Bookmarks::class,
            ])
            ->renderHook(
                PanelsRenderHook::SIDEBAR_NAV_END,
                fn (): View => view('filament-bookmarks-menu::sidebar'),
            );
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }
}
