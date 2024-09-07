<?php

namespace TomatoPHP\FilamentBookmarksMenu\Traits;

use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use TomatoPHP\FilamentBookmarksMenu\Facades\FilamentBookmarksMenu;
use TomatoPHP\FilamentBookmarksMenu\Models\Bookmark;

trait HasTypes
{
    public function types(){
        $bookmarkTypes = FilamentBookmarksMenu::load();
        $types = [];
        if(count($bookmarkTypes)){
            $types[] = Select::make('type')
                ->label(trans('filament-bookmarks-menu::messages.actions.types.form.type'))
                ->searchable()
                ->live()
                ->options(collect($bookmarkTypes)->filter(function ($item){
                    if($item->panel && $item->panel === filament()->getCurrentPanel()->getId()){
                        return true;
                    }
                    else if(!$item->panel){
                        return true;
                    }
                })->pluck('label', 'key')->toArray())
                ->required();
            $types[] = Select::make('bookmark_id')
                ->label(trans('filament-bookmarks-menu::messages.actions.types.form.bookmark'))
                ->multiple()
                ->disabled(fn(Get $get) => !$get('type'))
                ->searchable()
                ->options(fn(Get $get) => Bookmark::query()
                    ->where('type', $get('type'))
                    ->where('is_private', false)
                    ->orWhere('is_private', true)
                    ->where('user_type', get_class(auth()->user()))
                    ->where('user_id', auth()->id())
                    ->where('type', $get('type'))
                    ->get()
                    ->pluck('name', 'id')
                    ->toArray())
                ->required();
        }
        else {
            $types = [
                Select::make('bookmark_id')
                    ->label(trans('filament-bookmarks-menu::messages.actions.types.form.bookmark'))
                    ->multiple()
                    ->searchable()
                    ->options(Bookmark::query()->get()->pluck('name', 'id')->toArray())
                    ->required()
            ];
        }

        return $types;
    }
}
