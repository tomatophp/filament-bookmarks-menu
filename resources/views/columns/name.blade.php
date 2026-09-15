@php
    $link = $getRecord();
    $url = (string) $link->url;
    $isSafeUrl = str($url)->startsWith(['/', 'http://', 'https://']);
@endphp

<a
    @if ($isSafeUrl) href="{{ $url }}" @endif
    class="flex items-center justify-start gap-2 p-4"
>
    <x-filament::icon :icon="$link->icon ?: 'heroicon-s-link'" class="h-6 w-6" />

    <span>{{ $link->name }}</span>
</a>
