<?php

namespace TomatoPHP\FilamentBookmarksMenu\Facades;

use Illuminate\Support\Facades\Facade;
use TomatoPHP\FilamentBookmarksMenu\Services\FilamentBookmarksMenuServices;

/**
 * @see FilamentBookmarksMenuServices
 *
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
