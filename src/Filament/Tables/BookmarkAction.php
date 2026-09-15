<?php

namespace TomatoPHP\FilamentBookmarksMenu\Filament\Tables;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use TomatoPHP\FilamentBookmarksMenu\Traits\HasPage;
use TomatoPHP\FilamentBookmarksMenu\Traits\HasTypes;
use TomatoPHP\FilamentBookmarksMenu\Traits\InteractsWithBookmarkLinks;

/**
 * Record action for resource tables: bookmark / un-bookmark a record page.
 */
class BookmarkAction extends Action
{
    use HasPage;
    use HasTypes;
    use InteractsWithBookmarkLinks;

    public static function getDefaultName(): ?string
    {
        return 'table_bookmark';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->requiresConfirmation();
        $this->hiddenLabel();
        $this->tooltip(fn (Model $record): string => $this->isRecordBookmarked($record)
            ? trans('filament-bookmarks-menu::messages.actions.table.remove')
            : trans('filament-bookmarks-menu::messages.actions.table.add'));
        $this->modalHeading(fn (Model $record): string => $this->isRecordBookmarked($record)
            ? trans('filament-bookmarks-menu::messages.actions.table.modal.remove')
            : trans('filament-bookmarks-menu::messages.actions.table.modal.add'));
        $this->schema(fn (Model $record): array => $this->isRecordBookmarked($record) ? [] : $this->types());
        $this->color(fn (Model $record): string => $this->isRecordBookmarked($record) ? 'danger' : 'success');
        $this->icon(fn (Model $record): string => $this->isRecordBookmarked($record) ? 'heroicon-s-bookmark-slash' : 'heroicon-s-bookmark');
        $this->action(function (array $data, Model $record): void {
            $url = $this->getRecordPageUrl($record);
            $link = $this->findBookmarkLink($url);

            if (! $link) {
                $this->saveBookmarkLink($url, $record, $data['bookmark_id'] ?? []);

                Notification::make()
                    ->title(trans('filament-bookmarks-menu::messages.actions.page.notification.add.title'))
                    ->body(trans('filament-bookmarks-menu::messages.actions.page.notification.add.body'))
                    ->success()
                    ->send();

                return;
            }

            $link->removeFromBookmarksVisibleTo(auth()->user());

            Notification::make()
                ->title(trans('filament-bookmarks-menu::messages.actions.page.notification.remove.title'))
                ->body(trans('filament-bookmarks-menu::messages.actions.page.notification.remove.body'))
                ->success()
                ->send();
        });
    }

    protected function getRecordPageUrl(Model $record): string
    {
        return $this->getBookmarkResource()::getUrl($this->getPage(), ['record' => $record]);
    }

    protected function isRecordBookmarked(Model $record): bool
    {
        return (bool) $this->findBookmarkLink($this->getRecordPageUrl($record));
    }
}
