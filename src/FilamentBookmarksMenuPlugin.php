<?php

namespace TomatoPHP\FilamentBookmarksMenu;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Nwidart\Modules\Module;
use TomatoPHP\FilamentBookmarksMenu\Filament\Pages\Bookmarks;


class FilamentBookmarksMenuPlugin implements Plugin
{

    private bool $isActive = false;

    public function getId(): string
    {
        return 'filament-bookmarks-menu';
    }

    public function register(Panel $panel): void
    {
        if(class_exists(Module::class) && \Nwidart\Modules\Facades\Module::find('FilamentBookmarksMenu')?->isEnabled()){
            $this->isActive = true;
        }
        else {
            $this->isActive = true;
        }

        if($this->isActive) {
            $panel->pages([
                Bookmarks::class
            ]);
        }
    }

    public function boot(Panel $panel): void
    {
        if($this->isActive) {
            if ($panel->getId() === filament()->getCurrentPanel()->getId()) {
                FilamentView::registerRenderHook(
                    PanelsRenderHook::SIDEBAR_NAV_END,
                    fn() => view('filament-bookmarks-menu::sidebar')
                );
            }
        }

    }

    public static function make(): static
    {
        return new static();
    }
}
