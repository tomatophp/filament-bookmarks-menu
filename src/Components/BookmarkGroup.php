<?php

namespace TomatoPHP\FilamentBookmarksMenu\Components;

use Filament\Actions\Action;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BookmarkGroup extends Component
{
    public function __construct(
        public string $key,
        public string $label,
        public Action $action
    ) {}

    public function render(): View
    {
        return view('filament-bookmarks-menu::components.bookmark-group');
    }
}
