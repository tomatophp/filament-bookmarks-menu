<?php

namespace TomatoPHP\FilamentBookmarksMenu\Filament\Actions;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use TomatoPHP\FilamentBookmarksMenu\Traits\HasTypes;
use TomatoPHP\FilamentBookmarksMenu\Traits\InteractsWithBookmarkLinks;

/**
 * Header action for resource pages: bookmark / un-bookmark the current page.
 */
class BookmarkAction extends Action
{
    use HasTypes;
    use InteractsWithBookmarkLinks;

    public static function getDefaultName(): ?string
    {
        return 'bookmark';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->requiresConfirmation();
        $this->hiddenLabel();
        $this->tooltip(fn (?Model $record): string => $this->isPageBookmarked($record)
            ? trans('filament-bookmarks-menu::messages.actions.page.remove')
            : trans('filament-bookmarks-menu::messages.actions.page.add'));
        $this->modalHeading(fn (?Model $record): string => $this->isPageBookmarked($record)
            ? trans('filament-bookmarks-menu::messages.actions.page.modal.remove')
            : trans('filament-bookmarks-menu::messages.actions.page.modal.add'));
        $this->schema(fn (?Model $record): array => $this->isPageBookmarked($record) ? [] : $this->types());
        $this->color(fn (?Model $record): string => $this->isPageBookmarked($record) ? 'danger' : 'success');
        $this->icon(fn (?Model $record): string => $this->isPageBookmarked($record) ? 'heroicon-s-bookmark-slash' : 'heroicon-s-bookmark');
        $this->action(function (array $data, ?Model $record) {
            $url = $this->getPageUrl($record);
            $link = $this->findBookmarkLink($url);

            if (! $link) {
                $this->saveBookmarkLink($url, $record, $data['bookmark_id'] ?? []);

                Notification::make()
                    ->title(trans('filament-bookmarks-menu::messages.actions.page.notification.add.title'))
                    ->body(trans('filament-bookmarks-menu::messages.actions.page.notification.add.body'))
                    ->success()
                    ->send();
            } else {
                $link->removeFromBookmarksVisibleTo(auth()->user());

                Notification::make()
                    ->title(trans('filament-bookmarks-menu::messages.actions.page.notification.remove.title'))
                    ->body(trans('filament-bookmarks-menu::messages.actions.page.notification.remove.body'))
                    ->success()
                    ->send();
            }

            return redirect()->to($url);
        });
    }

    protected function getPageUrl(?Model $record): string
    {
        return $this->getLivewire()::getUrl($record ? ['record' => $record] : []);
    }

    protected function isPageBookmarked(?Model $record): bool
    {
        return (bool) $this->findBookmarkLink($this->getPageUrl($record));
    }
}
