<li
    x-data="{ label: 'sub_navigation_{{ $key }}' }"
    data-group-label="sub_navigation_{{ $key }}'"
    class="fi-sidebar-group flex flex-col gap-y-1"
>
    <div

        x-show="$store.sidebar.isOpen"

        class="fi-sidebar-group-button flex items-center gap-x-2 px-2 py-2 cursor-pointer"
    >

            <span
                x-on:click="$store.sidebar.toggleCollapsedGroup(label)"
                x-transition:enter="delay-100 lg:transition"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                class="fi-sidebar-group-label flex-1 text-sm font-medium leading-6 text-gray-500 dark:text-gray-400"
            >
                {{ $label }}
            </span>

        {{ $action??null }}

        <x-filament::icon-button
            color="gray"
            icon="heroicon-m-chevron-up"
            icon-alias="panels::sidebar.group.collapse-button"
            :label="$label"
            class="fi-sidebar-group-collapse-button"
            x-bind:aria-expanded="! $store.sidebar.groupIsCollapsed(label)"
            x-on:click.stop="$store.sidebar.toggleCollapsedGroup(label)"
            x-bind:class="{ '-rotate-180': $store.sidebar.groupIsCollapsed(label) }"
        />
    </div>

    <ul
        x-show="! $store.sidebar.groupIsCollapsed(label)"
        x-collapse.duration.200ms
        x-transition:enter="delay-100 lg:transition"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        class="fi-sidebar-group-items flex flex-col gap-y-1"
    >
        {{ $slot }}
    </ul>
</li>
