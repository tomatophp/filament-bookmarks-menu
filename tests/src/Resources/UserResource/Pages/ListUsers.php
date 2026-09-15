<?php

namespace TomatoPHP\FilamentBookmarksMenu\Tests\Resources\UserResource\Pages;

use Filament\Resources\Pages\ListRecords;
use TomatoPHP\FilamentBookmarksMenu\Tests\Resources\UserResource;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;
}
