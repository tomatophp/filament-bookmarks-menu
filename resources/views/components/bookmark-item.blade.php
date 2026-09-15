@php
    $isActive = (string) request()->query('id') === (string) $bookmark->id;
@endphp

<x-filament-panels::sidebar.item
    :active="$isActive"
    :icon="$bookmark->icon ?: 'heroicon-s-folder'"
    :badge="$bookmark->links_count ?? $bookmark->links()->count()"
    badge-color="primary"
    :badge-tooltip="trans('filament-bookmarks-menu::messages.components.total')"
    :url="\TomatoPHP\FilamentBookmarksMenu\Filament\Pages\Bookmarks::getUrl(['id' => $bookmark->id])"
>
    {{ $bookmark->name }}
</x-filament-panels::sidebar.item>
