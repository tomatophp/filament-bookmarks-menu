<?php

namespace TomatoPHP\FilamentBookmarksMenu;

use Filament\Contracts\Plugin;
use Filament\Panel;


class FilamentBookmarksMenuPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-bookmarks-menu';
    }

    public function register(Panel $panel): void
    {
        //
    }


    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return new static();
    }
}
