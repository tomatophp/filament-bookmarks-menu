<?php

namespace TomatoPHP\FilamentBookmarksMenu\Traits;

use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;
use TomatoPHP\FilamentBookmarksMenu\Facades\FilamentBookmarksMenu;
use TomatoPHP\FilamentBookmarksMenu\Models\Bookmark;
use TomatoPHP\FilamentBookmarksMenu\Services\Contracts\BookmarkType;

trait HasTypes
{
    /**
     * @return array<int, Select>
     */
    public function types(): array
    {
        $panelId = filament()->getCurrentOrDefaultPanel()?->getId();

        $bookmarkTypes = collect(FilamentBookmarksMenu::load())
            ->filter(fn (BookmarkType $type): bool => ! $type->panel || $type->panel === $panelId);

        if ($bookmarkTypes->isEmpty()) {
            return [
                Select::make('bookmark_id')
                    ->label(trans('filament-bookmarks-menu::messages.actions.types.form.bookmark'))
                    ->multiple()
                    ->searchable()
                    ->options(fn (): array => Bookmark::query()
                        ->visibleTo(auth()->user())
                        ->pluck('name', 'id')
                        ->toArray())
                    ->required(),
            ];
        }

        return [
            Select::make('type')
                ->label(trans('filament-bookmarks-menu::messages.actions.types.form.type'))
                ->searchable()
                ->live()
                ->options($bookmarkTypes->pluck('label', 'key')->toArray())
                ->required(),
            Select::make('bookmark_id')
                ->label(trans('filament-bookmarks-menu::messages.actions.types.form.bookmark'))
                ->multiple()
                ->disabled(fn (Get $get): bool => ! $get('type'))
                ->searchable()
                ->options(fn (Get $get): array => Bookmark::query()
                    ->where('type', $get('type'))
                    ->visibleTo(auth()->user())
                    ->pluck('name', 'id')
                    ->toArray())
                ->required(),
        ];
    }
}
