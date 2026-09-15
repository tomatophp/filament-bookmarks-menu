<div class="fi-bookmarks-menu">
    <ul class="fi-sidebar-nav-groups">
        @foreach ($this->getGroups() as $group)
            <x-filament-bookmark-group
                :key="$group['key']"
                :label="$group['label']"
                :action="($this->createAction)(['type' => $group['key']])"
            >
                @foreach ($this->getBookmarks($group['key']) as $bookmark)
                    <x-filament-bookmark-item :bookmark="$bookmark" />
                @endforeach
            </x-filament-bookmark-group>
        @endforeach
    </ul>

    <x-filament-actions::modals />
</div>
