<?php

namespace TomatoPHP\FilamentBookmarksMenu\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use TomatoPHP\FilamentBookmarksMenu\Models\Bookmark;

class BookmarkItem extends Component
{
    public function __construct(
        public Bookmark $bookmark,
    ) {}

    public function render(): View
    {
        return view('filament-bookmarks-menu::components.bookmark-item');
    }
}
