<?php

namespace TomatoPHP\FilamentBookmarksMenu\Filament\Tables;


use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use TomatoPHP\FilamentBookmarksMenu\Models\Bookmark;
use TomatoPHP\FilamentBookmarksMenu\Models\BookmarkLink;
use TomatoPHP\FilamentBookmarksMenu\Traits\HasPage;
use TomatoPHP\FilamentBookmarksMenu\Traits\HasTypes;

class BookmarkAction extends Action
{
    use HasPage;
    use HasTypes;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->name('table_bookmark');
        $this->requiresConfirmation();
        $this->tooltip(function ($record){
            return BookmarkLink::query()->where('url', app($this->getLivewire()::getResource())::getUrl($this->getPage(),['record' => $record]))->first() ? trans('filament-bookmarks-menu::messages.actions.table.remove') : trans('filament-bookmarks-menu::messages.actions.table.add');
        });
        $this->modalHeading(function ($record){
            return BookmarkLink::query()->where('url', app($this->getLivewire()::getResource())::getUrl($this->getPage(),['record' => $record]))->first() ? trans('filament-bookmarks-menu::messages.actions.table.modal.remove') : trans('filament-bookmarks-menu::messages.actions.table.modal.add');
        });
        $this->hiddenLabel();
        $this->form(function($record){
            $resource = app($this->getLivewire()::getResource());
            $checkExists = BookmarkLink::query()->where('url', $resource::getUrl($this->getPage(),['record' => $record]))->first();
            return $checkExists ? [] : $this->types();
        }
        );
        $this->color(fn($record) => BookmarkLink::query()->where('url',app($this->getLivewire()::getResource())::getUrl($this->getPage(),['record' => $record]))->first() ? 'danger' : 'success');
        $this->icon(fn($record) => BookmarkLink::query()->where('url', app($this->getLivewire()::getResource())::getUrl($this->getPage(),['record' => $record]))->first() ? 'heroicon-s-bookmark-slash' : 'heroicon-s-bookmark');
        $this->action(function (array $data, $record) {
            $checkExists = BookmarkLink::query()->where('url', app($this->getLivewire()::getResource())::getUrl($this->getPage(),['record' => $record]))->first();
            if(!$checkExists){
                $resource = app($this->getLivewire()::getResource());
                $bookmarkLink = BookmarkLink::create([
                    'name' => $record ? ($record->{$resource->getRecordTitleAttribute()} ?: ($record->name?: ($record->title ?: $this->getLivewire()->getTitle()))) : $this->getLivewire()->getTitle(),
                    'url' => $resource::getUrl($this->getPage(),['record' => $record]),
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
