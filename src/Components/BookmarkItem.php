<?php

namespace TomatoPHP\FilamentBookmarksMenu\Components;

use Illuminate\View\Component;
use TomatoPHP\FilamentBookmarksMenu\Models\Bookmark;

class BookmarkItem extends Component
{
    public function __construct(
        public Bookmark $bookmark,
    )
    {
    }

    public function render()
    {
        return view('filament-bookmarks-menu::components.bookmark-item');
    }
}
