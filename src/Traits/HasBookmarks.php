<?php

namespace TomatoPHP\FilamentBookmarksMenu\Traits;

use TomatoPHP\FilamentBookmarksMenu\Models\Bookmark;

trait HasBookmarks
{
    public function bookmarks()
    {
        return $this->morphToMany(Bookmark::class, 'bookmarkable', 'bookmarkable', 'bookmarkable_id');
    }
}
