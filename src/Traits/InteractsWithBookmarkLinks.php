<?php

namespace TomatoPHP\FilamentBookmarksMenu\Traits;

use BackedEnum;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use TomatoPHP\FilamentBookmarksMenu\Models\BookmarkLink;

trait InteractsWithBookmarkLinks
{
    /**
     * @return class-string<\Filament\Resources\Resource>
     */
    protected function getBookmarkResource(): string
    {
        return $this->getLivewire()::getResource();
    }

    protected function findBookmarkLink(string $url): ?BookmarkLink
    {
        return BookmarkLink::findVisibleByUrl($url, auth()->user());
    }

    /**
     * @param  array<int, int|string>|int|string  $bookmarkIds
     */
    protected function saveBookmarkLink(string $url, ?Model $record, array|int|string $bookmarkIds): BookmarkLink
    {
        return BookmarkLink::saveToBookmarks($url, [
            'name' => $this->getBookmarkName($record),
            'icon' => $this->getBookmarkIcon(),
            'color' => 'primary',
        ], $bookmarkIds, auth()->user());
    }

    protected function getBookmarkName(?Model $record): string
    {
        $resource = $this->getBookmarkResource();

        if ($record) {
            $title = strip_tags((string) $resource::getRecordTitle($record));

            if (filled($title)) {
                return $title;
            }
        }

        return strip_tags((string) $this->getLivewire()->getTitle());
    }

    protected function getBookmarkIcon(): string
    {
        $icon = $this->getBookmarkResource()::getNavigationIcon();

        if ($icon instanceof Heroicon) {
            return 'heroicon-'.$icon->value;
        }

        if ($icon instanceof BackedEnum) {
            return (string) $icon->value;
        }

        return filled($icon) ? (string) $icon : 'heroicon-s-bookmark';
    }
}
