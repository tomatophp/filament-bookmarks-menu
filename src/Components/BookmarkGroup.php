<?php

namespace TomatoPHP\FilamentBookmarksMenu\Components;

use Filament\Actions\Action;
use Illuminate\View\Component;

class BookmarkGroup extends Component
{

    public function __construct(
        public string $key,
        public string $label,
        public Action $action
    )
    {
    }

    public function render()
    {
        return view('filament-bookmarks-menu::components.bookmark-group');
    }
}
