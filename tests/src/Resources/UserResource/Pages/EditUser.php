<?php

namespace TomatoPHP\FilamentBookmarksMenu\Tests\Resources\UserResource\Pages;

use Filament\Resources\Pages\EditRecord;
use TomatoPHP\FilamentBookmarksMenu\Filament\Actions\BookmarkAction;
use TomatoPHP\FilamentBookmarksMenu\Tests\Resources\UserResource;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            BookmarkAction::make(),
        ];
    }
}
