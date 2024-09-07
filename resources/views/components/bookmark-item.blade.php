@php $isActive = request()->get('id') == $bookmark->id @endphp
<li
    class="fi-sidebar-item @if($isActive) fi-active  fi-sidebar-item-active @endif "
>
    <a
        href="{{ \TomatoPHP\FilamentBookmarksMenu\Filament\Pages\Bookmarks::getUrl() . '?id=' . $bookmark->id}}"
        x-on:click="window.matchMedia(`(max-width: 1024px)`).matches && $store.sidebar.close()"
        x-data="{ tooltip: '' }"
        x-effect="
            tooltip = $store.sidebar.isOpen
            ? false
            : {
                  content: '{{ $bookmark->name }}',
                  placement: document.dir === 'rtl' ? 'left' : 'right',
                  theme: $store.theme,
              }
        "
        x-tooltip.html="tooltip"
        class="@if($isActive) bg-gray-100 dark:bg-white/5 @endif  fi-sidebar-item-button relative flex items-center justify-center gap-x-3 rounded-lg px-2 py-2 outline-none transition duration-75 hover:bg-gray-100 focus-visible:bg-gray-100 dark:hover:bg-white/5 dark:focus-visible:bg-white/5"
    >
            <x-filament::icon
                icon="{{ $bookmark->icon ?: 'heroicon-s-folder' }}"
                x-show="$store.sidebar.isOpen"
                class="fi-sidebar-item-icon h-6 w-6 text-gray-400 dark:text-gray-500"
                style="color: {{ $bookmark->color ?: '' }}"
            />

            <span

                x-show="$store.sidebar.isOpen"
                x-transition:enter="lg:transition lg:delay-100"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                class="fi-sidebar-item-label flex-1 truncate text-sm font-medium @if($isActive) text-primary-600 dark:text-primary-400 @else text-gray-700 dark:text-gray-200 @endif"
            >
                {{ $bookmark->name }}
            </span>

        <span

            x-show="$store.sidebar.isOpen"
            x-transition:enter="lg:transition lg:delay-100"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"

        >
            <x-filament::badge
                color="primary"
                tooltip="{{ trans('filament-bookmarks-menu::messages.components.total') }}"
            >
                {{ $bookmark->links?->count() ?: 0 }}
            </x-filament::badge>
        </span>
    </a>
</li>
