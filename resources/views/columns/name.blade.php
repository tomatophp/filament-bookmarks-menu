@php $link = \TomatoPHP\FilamentBookmarksMenu\Models\BookmarkLink::query()->where('url', $getState())->first() @endphp

<a href="{{ $link->url }}" class="flex justify-start gap-2 p-4">
    <div class="flex flex-col justify-center items-center">
       <x-icon :name="$link->icon" class="w-6 h-6" />
    </div>
    <div>
        {{ $link->name }}
    </div>
</a>
