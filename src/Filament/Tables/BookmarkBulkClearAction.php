<?php

namespace TomatoPHP\FilamentBookmarksMenu\Filament\Tables;

use Filament\Actions\BulkAction;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use TomatoPHP\FilamentBookmarksMenu\Traits\HasPage;
use TomatoPHP\FilamentBookmarksMenu\Traits\InteractsWithBookmarkLinks;

/**
 * Bulk action for resource tables: remove the selected record pages from the user's bookmarks.
 */
class BookmarkBulkClearAction extends BulkAction
{
    use HasPage;
    use InteractsWithBookmarkLinks;

    public static function getDefaultName(): ?string
    {
        return 'bulk_clear_bookmark';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->requiresConfirmation();
        $this->label(trans('filament-bookmarks-menu::messages.actions.bulk.remove'));
        $this->modalHeading(trans('filament-bookmarks-menu::messages.actions.bulk.modal.remove'));
        $this->color('danger');
        $this->deselectRecordsAfterCompletion();
        $this->icon('heroicon-s-bookmark-slash');
        $this->action(function (Collection $records): void {
            $records->each(function (Model $record): void {
                $url = $this->getBookmarkResource()::getUrl($this->getPage(), ['record' => $record]);

                $this->findBookmarkLink($url)?->removeFromBookmarksVisibleTo(auth()->user());
            });

            Notification::make()
                ->title(trans('filament-bookmarks-menu::messages.actions.page.notification.remove.title'))
                ->body(trans('filament-bookmarks-menu::messages.actions.page.notification.remove.body'))
                ->success()
                ->send();
        });
    }
}
