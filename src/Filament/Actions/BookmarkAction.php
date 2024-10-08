<?php

namespace TomatoPHP\FilamentBookmarksMenu\Filament\Actions;

use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use TomatoPHP\FilamentBookmarksMenu\Facades\FilamentBookmarksMenu;
use TomatoPHP\FilamentBookmarksMenu\Models\Bookmark;
use TomatoPHP\FilamentBookmarksMenu\Models\BookmarkLink;
use TomatoPHP\FilamentBookmarksMenu\Traits\HasPage;
use TomatoPHP\FilamentBookmarksMenu\Traits\HasTypes;

class BookmarkAction extends Action
{
    use HasTypes;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->name('bookmark');
        $this->tooltip(function ($record){
            return BookmarkLink::query()->where('url', $this->livewire::getUrl(['record' => $record]))->first() ? trans('filament-bookmarks-menu::messages.actions.page.remove') : trans('filament-bookmarks-menu::messages.actions.page.add');
        });
        $this->modalHeading(function ($record){
            return BookmarkLink::query()->where('url', $this->livewire::getUrl(['record' => $record]))->first() ? trans('filament-bookmarks-menu::messages.actions.page.modal.remove') : trans('filament-bookmarks-menu::messages.actions.page.modal.add');
        });
        $this->requiresConfirmation();
        $this->hiddenLabel();
        $this->form(function($record){
            return BookmarkLink::query()->where('url', $this->livewire::getUrl(['record' => $record]))->first() ? [] : $this->types();
        });
        $this->color(fn($record) => BookmarkLink::query()->where('url', $this->livewire::getUrl(['record' => $record]))->first() ? 'danger' : 'success');
        $this->icon(fn($record) => BookmarkLink::query()->where('url', $this->livewire::getUrl(['record' => $record]))->first() ? 'heroicon-s-bookmark-slash' : 'heroicon-s-bookmark');
        $this->action(function (array $data, $record) {
            $checkExists = BookmarkLink::query()->where('url', $this->livewire::getUrl(['record' => $record]))->first();
            if(!$checkExists){
                $resource = app($this->livewire::getResource());
                $bookmarkLink = BookmarkLink::create([
                    'name' => $record ? ($record->{$resource->getRecordTitleAttribute()} ?: ($record->name?: ($record->title ?: $this->livewire->getTitle()))) : $this->livewire->getTitle(),
                    'url' => $this->livewire::getUrl(['record' => $record]),
                    'icon' => $resource::getNavigationIcon() ?: 'heroicon-s-bookmark',
                    'color' => 'primary',
                ]);

                $bookmarkLink->bookmarks()->attach($data['bookmark_id']);

                Notification::make()
                    ->title(trans('filament-bookmarks-menu::messages.actions.page.notification.add.title'))
                    ->body(trans('filament-bookmarks-menu::messages.actions.page.notification.add.body'))
                    ->success()
                    ->send();
            }
            else {
                $checkExists->delete();

                Notification::make()
                    ->title(trans('filament-bookmarks-menu::messages.actions.page.notification.remove.title'))
                    ->body(trans('filament-bookmarks-menu::messages.actions.page.notification.remove.body'))
                    ->success()
                    ->send();
            }



            return redirect()->back();
        });
    }
}
