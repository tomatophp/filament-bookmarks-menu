<?php

namespace TomatoPHP\FilamentBookmarksMenu\Services;

use TomatoPHP\FilamentBookmarksMenu\Services\Contracts\BookmarkType;

class FilamentBookmarksMenuServices
{
    /**
     * @var array<int, BookmarkType>
     */
    public array $types = [];

    /**
     * @param  array<int, BookmarkType>|BookmarkType  $bookmarkType
     */
    public function register(array|BookmarkType $bookmarkType): void
    {
        foreach (is_array($bookmarkType) ? $bookmarkType : [$bookmarkType] as $type) {
            $this->types[] = $type;
        }
    }

    /**
     * @return array<int, BookmarkType>
     */
    public function load(): array
    {
        return $this->types;
    }
}
