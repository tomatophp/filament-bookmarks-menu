<?php

namespace TomatoPHP\FilamentBookmarksMenu\Filament\Tables;

use Filament\Actions\BulkAction;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use TomatoPHP\FilamentBookmarksMenu\Traits\HasPage;
use TomatoPHP\FilamentBookmarksMenu\Traits\HasTypes;
use TomatoPHP\FilamentBookmarksMenu\Traits\InteractsWithBookmarkLinks;

/**
 * Bulk action for resource tables: bookmark the selected record pages.
 */
class BookmarkBulkAction extends BulkAction
{
    use HasPage;
    use HasTypes;
    use InteractsWithBookmarkLinks;

    public static function getDefaultName(): ?string
    {
        return 'bulk_bookmark';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->requiresConfirmation();
        $this->label(trans('filament-bookmarks-menu::messages.actions.bulk.add'));
        $this->modalHeading(trans('filament-bookmarks-menu::messages.actions.bulk.modal.add'));
        $this->schema(fn (): array => $this->types());
        $this->color('success');
        $this->deselectRecordsAfterCompletion();
        $this->icon('heroicon-s-bookmark');
        $this->action(function (array $data, Collection $records): void {
            $records->each(function (Model $record) use ($data): void {
                $url = $this->getBookmarkResource()::getUrl($this->getPage(), ['record' => $record]);

                $this->saveBookmarkLink($url, $record, $data['bookmark_id'] ?? []);
            });

            Notification::make()
                ->title(trans('filament-bookmarks-menu::messages.actions.page.notification.add.title'))
                ->body(trans('filament-bookmarks-menu::messages.actions.page.notification.add.body'))
                ->success()
                ->send();
        });
    }
}
