<?php

namespace TomatoPHP\FilamentBookmarksMenu\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \TomatoPHP\FilamentBookmarksMenu\Services\FilamentBookmarksMenuServices
 * @method static void register(array $bookmarkType)
 * @method static array load()
 */
class FilamentBookmarksMenu extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'filament-bookmarks-menu';
    }
}
