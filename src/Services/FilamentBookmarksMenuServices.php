<?php

namespace TomatoPHP\FilamentBookmarksMenu\Services;

use TomatoPHP\FilamentBookmarksMenu\Services\Contracts\BookmarkType;

class FilamentBookmarksMenuServices
{
    public array $types = [];

    public function register(array|BookmarkType $bookmarkType)
    {
        if(is_array($bookmarkType)) {
            foreach ($bookmarkType as $type) {
                $this->types[] = $type;
            }
        }
        else {
            $this->types[] = $bookmarkType;
        }
    }

    public function load(): array
    {
        return $this->types;
    }
}
