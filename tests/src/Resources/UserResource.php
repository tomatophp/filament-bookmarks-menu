<?php

namespace TomatoPHP\FilamentBookmarksMenu\Tests\Resources;

use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use TomatoPHP\FilamentBookmarksMenu\Filament\Tables\BookmarkAction;
use TomatoPHP\FilamentBookmarksMenu\Filament\Tables\BookmarkBulkAction;
use TomatoPHP\FilamentBookmarksMenu\Filament\Tables\BookmarkBulkClearAction;
use TomatoPHP\FilamentBookmarksMenu\Tests\Models\User;
use TomatoPHP\FilamentBookmarksMenu\Tests\Resources\UserResource\Pages\EditUser;
use TomatoPHP\FilamentBookmarksMenu\Tests\Resources\UserResource\Pages\ListUsers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
            ])
            ->recordActions([
                BookmarkAction::make(),
            ])
            ->toolbarActions([
                BookmarkBulkAction::make(),
                BookmarkBulkClearAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
