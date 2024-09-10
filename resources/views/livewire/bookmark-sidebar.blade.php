<div class="fi-sidebar-nav-groups -mx-2 flex flex-col gap-y-7">
    @php $groups = \TomatoPHP\FilamentBookmarksMenu\Facades\FilamentBookmarksMenu::load(); @endphp
    @if(count($groups))
        @foreach($groups as $group)
            @if($group->panel && $group->panel === filament()->getCurrentPanel()->getId())
                <x-filament-bookmark-group
                    :key="$group->key"
                    :label="str($group->label)->contains('.') ? trans($group->label) : $group->label"
                    :action="($this->getCreateAction($group->key))(['type' => $group->key])"
                >
                    @php $folders = \TomatoPHP\FilamentBookmarksMenu\Models\Bookmark::query()
            ->where('type', $group->key)
            ->where('is_private', false)
            ->orWhere('is_private', true)
            ->where('user_type', get_class(auth()->user()))
            ->where('user_id', auth()->id())
            ->where('type', $group->key)
            ->get() @endphp
                    @foreach($folders as $folder)
                        <x-filament-bookmark-item :bookmark="$folder"/>
                    @endforeach

                </x-filament-bookmark-group>
            @elseif(!$group->panel)
                <x-filament-bookmark-group
                    :key="$group->key"
                    :label="str($group->label)->contains('.') ? trans($group->label) : $group->label"
                    :action="($this->getCreateAction($group->key))(['type' => $group->key])"
                >
                    @php $folders = \TomatoPHP\FilamentBookmarksMenu\Models\Bookmark::query()
            ->where('type', $group->key)
            ->where('is_private', false)
            ->orWhere('is_private', true)
            ->where('user_type', get_class(auth()->user()))
            ->where('user_id', auth()->id())
            ->where('type', $group->key)
            ->get() @endphp
                    @foreach($folders as $folder)
                        <x-filament-bookmark-item :bookmark="$folder"/>
                    @endforeach

                </x-filament-bookmark-group>
            @endif

        @endforeach
    @else
        <x-filament-bookmark-group
            key="folders"
            :label="trans('filament-bookmarks-menu::messages.components.folders')"
            :action="($this->getCreateAction('folder'))(['type' => 'folder'])"
        >
            @php $folders = \TomatoPHP\FilamentBookmarksMenu\Models\Bookmark::query()
            ->where('type', 'folder')
            ->where('is_private', false)
            ->orWhere('is_private', true)
            ->where('user_type', get_class(auth()->user()))
            ->where('user_id', auth()->id())
            ->where('type', 'folder')
            ->get() @endphp
            @foreach($folders as $folder)
                <x-filament-bookmark-item :bookmark="$folder"/>
            @endforeach

        </x-filament-bookmark-group>
    @endif


    <x-filament-actions::modals />

</div>
