<?php

namespace TomatoPHP\FilamentBookmarksMenu\Traits;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use TomatoPHP\FilamentBookmarksMenu\Models\Bookmark;

trait HasBookmarks
{
    public function bookmarks(): MorphToMany
    {
        return $this->morphToMany(Bookmark::class, 'bookmarkable', 'bookmarkable', 'bookmarkable_id');
    }
}
