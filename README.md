![Screenshot](https://raw.githubusercontent.com/tomatophp/filament-bookmarks-menu/master/arts/3x1io-tomato-bookmarks-menu.jpg)

# Filament Bookmarks Menu

[![Latest Stable Version](https://poser.pugx.org/tomatophp/filament-bookmarks-menu/version.svg)](https://packagist.org/packages/tomatophp/filament-bookmarks-menu)
[![License](https://poser.pugx.org/tomatophp/filament-bookmarks-menu/license.svg)](https://packagist.org/packages/tomatophp/filament-bookmarks-menu)
[![Downloads](https://poser.pugx.org/tomatophp/filament-bookmarks-menu/d/total.svg)](https://packagist.org/packages/tomatophp/filament-bookmarks-menu)

Add bookmarks and tags to your resources records and access theme form your sidebar

## Screenshots

![Bookmark Menu](https://raw.githubusercontent.com/tomatophp/filament-bookmarks-menu/master/arts/bookmark-menu.png)
![Create Modal](https://raw.githubusercontent.com/tomatophp/filament-bookmarks-menu/master/arts/create-modal.png)
![Add Bookmark](https://raw.githubusercontent.com/tomatophp/filament-bookmarks-menu/master/arts/add-bookmark.png)
![Remove Bookmark](https://raw.githubusercontent.com/tomatophp/filament-bookmarks-menu/master/arts/remove-bookmark.png)
![Bookmark Page](https://raw.githubusercontent.com/tomatophp/filament-bookmarks-menu/master/arts/bookmark-page.png)
![Bookmark Actions](https://raw.githubusercontent.com/tomatophp/filament-bookmarks-menu/master/arts/bookmark-actions.png)
![Bookmark Edit](https://raw.githubusercontent.com/tomatophp/filament-bookmarks-menu/master/arts/bookmark-edit.png)
![Bookmark Bulk Actions](https://raw.githubusercontent.com/tomatophp/filament-bookmarks-menu/master/arts/bulk-actions.png)


## Installation

```bash
composer require tomatophp/filament-bookmarks-menu
```
after install your package please run this command

```bash
php artisan filament-bookmarks-menu:install
```


finally register the plugin on `/app/Providers/Filament/AdminPanelProvider.php`

```php
->plugin(\TomatoPHP\FilamentBookmarksMenu\FilamentBookmarksMenuPlugin::make())
```

## Usage

you can add bookmark action to your page like this

```php
use TomatoPHP\FilamentBookmarksMenu\Filament\Actions\BookmarkAction;

protected function getHeaderActions(): array
{
    return [
        BookmarkAction::make()
    ];
}
```

or to your table like this

```php

use TomatoPHP\FilamentBookmarksMenu\Filament\Tables\BookmarkAction;

public function table(Table $table): void
{
    $table->actions([
        BookmarkAction::make()
    ]);
}
```

or to your table bulk actions like this


```php
use TomatoPHP\FilamentBookmarksMenu\Filament\Tables\BookmarkBulkAction;
use TomatoPHP\FilamentBookmarksMenu\Filament\Tables\BookmarkBulkClearAction;

public function table(Table $table): void
{
    $table->bulkActions([
        Tables\Actions\BulkActionGroup::make([
            BookmarkBulkAction::make(),
            BookmarkBulkClearAction::make()
        ]),
    ]);
}
```

## Create Custom Bookmark Type

you can create custom bookmark type by use our Facade `TomatoPHP\FilamentBookmarksMenu\Facades\FilamentBookmarksMenu` register method like this

```php
use TomatoPHP\FilamentBookmarksMenu\Facades\FilamentBookmarksMenu;
use TomatoPHP\FilamentBookmarksMenu\Services\Contracts\BookmarkType;
        

public function boot()
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

## Support

you can join our discord server to get support [TomatoPHP](https://discord.gg/Xqmt35Uh)

## Docs

you can check docs of this package on [Docs](https://docs.tomatophp.com/plugins/laravel-package-generator)

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security

Please see [SECURITY](SECURITY.md) for more information about security.

## Credits

- [Fady Mondy](mailto:info@3x1.io)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
