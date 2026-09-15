![Screenshot](https://raw.githubusercontent.com/tomatophp/filament-bookmarks-menu/master/arts/3x1io-tomato-bookmarks-menu.jpg)

# Filament Bookmarks Menu

[![Latest Stable Version](https://poser.pugx.org/tomatophp/filament-bookmarks-menu/version.svg)](https://packagist.org/packages/tomatophp/filament-bookmarks-menu)
[![License](https://poser.pugx.org/tomatophp/filament-bookmarks-menu/license.svg)](https://packagist.org/packages/tomatophp/filament-bookmarks-menu)
[![Downloads](https://poser.pugx.org/tomatophp/filament-bookmarks-menu/d/total.svg)](https://packagist.org/packages/tomatophp/filament-bookmarks-menu)
[![Tests](https://github.com/tomatophp/filament-bookmarks-menu/actions/workflows/tests.yml/badge.svg)](https://github.com/tomatophp/filament-bookmarks-menu/actions/workflows/tests.yml)

Add bookmarks to your resource records and pages, group them in collections and open them from your panel sidebar.

## Screenshots

![Bookmark Menu](https://raw.githubusercontent.com/tomatophp/filament-bookmarks-menu/master/arts/bookmark-menu.png)
![Create Modal](https://raw.githubusercontent.com/tomatophp/filament-bookmarks-menu/master/arts/create-modal.png)
![Add Bookmark](https://raw.githubusercontent.com/tomatophp/filament-bookmarks-menu/master/arts/add-bookmark.png)
![Remove Bookmark](https://raw.githubusercontent.com/tomatophp/filament-bookmarks-menu/master/arts/remove-bookmark.png)
![Bookmark Page](https://raw.githubusercontent.com/tomatophp/filament-bookmarks-menu/master/arts/bookmark-page.png)
![Bookmark Actions](https://raw.githubusercontent.com/tomatophp/filament-bookmarks-menu/master/arts/bookmark-actions.png)
![Bookmark Edit](https://raw.githubusercontent.com/tomatophp/filament-bookmarks-menu/master/arts/bookmark-edit.png)
![Bookmark Bulk Actions](https://raw.githubusercontent.com/tomatophp/filament-bookmarks-menu/master/arts/bulk-actions.png)

## Compatibility

| Version | Filament | Laravel | PHP |
|---------|----------|---------|-----|
| 5.x     | 5.x      | 12.x, 13.x | 8.2+ |
| 1.x ([v3 branch](https://github.com/tomatophp/filament-bookmarks-menu/tree/v3)) | 3.x | 10.x, 11.x | 8.1+ |

## Installation

```bash
composer require tomatophp/filament-bookmarks-menu
```

after install your package please run this command (it runs the package migrations)

```bash
php artisan filament-bookmarks-menu:install
```

finally register the plugin on `/app/Providers/Filament/AdminPanelProvider.php`

```php
->plugin(\TomatoPHP\FilamentBookmarksMenu\FilamentBookmarksMenuPlugin::make())
```

The plugin adds the bookmark collections to the end of the panel sidebar (`PanelsRenderHook::SIDEBAR_NAV_END`) and registers
a hidden `bookmarks` page (`/admin/bookmarks?id={bookmark}`) that lists the links saved in a collection.

## Usage

you can add bookmark action to your page like this

```php
use TomatoPHP\FilamentBookmarksMenu\Filament\Actions\BookmarkAction;

protected function getHeaderActions(): array
{
    return [
        BookmarkAction::make(),
    ];
}
```

or to your table record actions like this

```php
use TomatoPHP\FilamentBookmarksMenu\Filament\Tables\BookmarkAction;

public static function table(Table $table): Table
{
    return $table->recordActions([
        BookmarkAction::make(), // links to the "edit" page, use ->page('view') to bookmark the view page
    ]);
}
```

or to your table bulk actions like this

```php
use Filament\Actions\BulkActionGroup;
use TomatoPHP\FilamentBookmarksMenu\Filament\Tables\BookmarkBulkAction;
use TomatoPHP\FilamentBookmarksMenu\Filament\Tables\BookmarkBulkClearAction;

public static function table(Table $table): Table
{
    return $table->toolbarActions([
        BulkActionGroup::make([
            BookmarkBulkAction::make(),
            BookmarkBulkClearAction::make(),
        ]),
    ]);
}
```

## Private Bookmarks

A bookmark collection marked as **private** is only visible to the user who marked it: it is hidden from other users' sidebars,
its page returns 404 for them, and they cannot add links to it or remove links from it.

## Create Custom Bookmark Type

you can create custom bookmark type by use our Facade `TomatoPHP\FilamentBookmarksMenu\Facades\FilamentBookmarksMenu` register method like this

```php
use TomatoPHP\FilamentBookmarksMenu\Facades\FilamentBookmarksMenu;
use TomatoPHP\FilamentBookmarksMenu\Services\Contracts\BookmarkType;

public function boot(): void
{
    FilamentBookmarksMenu::register([
        BookmarkType::make('hashtags')->label('Hashtags')->panel('employee'),
        BookmarkType::make('folder')->label('Folders'),
    ]);
}
```

to make label translatable you can use your path direct on label like this `->label('filament.bookmarks-menu::labels.hashtags')`

## Publish Assets

you can publish config file by use this command

```bash
php artisan vendor:publish --tag="filament-bookmarks-menu-config"
```

you can publish views file by use this command

```bash
php artisan vendor:publish --tag="filament-bookmarks-menu-views"
```

you can publish languages file by use this command

```bash
php artisan vendor:publish --tag="filament-bookmarks-menu-lang"
```

you can publish migrations file by use this command

```bash
php artisan vendor:publish --tag="filament-bookmarks-menu-migrations"
```

## Testing

```bash
composer test
```

## Other Filament Packages

Checkout our [Awesome TomatoPHP](https://github.com/tomatophp/awesome)
