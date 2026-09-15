@php
    use Filament\Support\Icons\Heroicon;
    use Filament\View\PanelsIconAlias;

    $groupLabel = 'bookmarks_' . $key;
@endphp

<li
    x-data="{ label: @js($groupLabel) }"
    data-group-label="{{ $groupLabel }}"
    x-bind:class="{ 'fi-collapsed': $store.sidebar.groupIsCollapsed(label) }"
    class="fi-sidebar-group fi-collapsible"
>
    <div
        x-on:click="$store.sidebar.toggleCollapsedGroup(label)"
        x-show="$store.sidebar.isOpen"
        class="fi-sidebar-group-btn"
    >
        <span class="fi-sidebar-group-label">
            {{ $label }}
        </span>

        <span x-on:click.stop>
            {{ $action }}
        </span>

        <x-filament::icon-button
            color="gray"
            :icon="Heroicon::ChevronUp"
            :icon-alias="PanelsIconAlias::SIDEBAR_GROUP_COLLAPSE_BUTTON"
            :label="$label"
            x-bind:aria-expanded="! $store.sidebar.groupIsCollapsed(label)"
            x-on:click.stop="$store.sidebar.toggleCollapsedGroup(label)"
            class="fi-sidebar-group-collapse-btn"
        />
    </div>

    <ul
        x-show="! $store.sidebar.groupIsCollapsed(label)"
        x-collapse.duration.200ms
        class="fi-sidebar-group-items"
    >
        {{ $slot }}
    </ul>
</li>
