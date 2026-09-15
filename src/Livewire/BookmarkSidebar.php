<?php

namespace TomatoPHP\FilamentBookmarksMenu\Livewire;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use TomatoPHP\FilamentBookmarksMenu\Facades\FilamentBookmarksMenu;
use TomatoPHP\FilamentBookmarksMenu\Filament\Pages\Bookmarks;
use TomatoPHP\FilamentBookmarksMenu\Models\Bookmark;
use TomatoPHP\FilamentBookmarksMenu\Services\Contracts\BookmarkType;

class BookmarkSidebar extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public function createAction(): Action
    {
        return Action::make('create')
            ->iconButton()
            ->icon('heroicon-s-plus-circle')
            ->tooltip(trans('filament-bookmarks-menu::messages.livewire.create.label'))
            ->color('gray')
            ->label(trans('filament-bookmarks-menu::messages.livewire.create.label'))
            ->modalHeading(trans('filament-bookmarks-menu::messages.livewire.create.modal'))
            ->schema([
                TextInput::make('name')
                    ->label(trans('filament-bookmarks-menu::messages.livewire.create.form.name'))
                    ->unique('bookmarks', 'name')
                    ->maxLength(255)
                    ->required(),
            ])
            ->action(function (array $data, array $arguments) {
                $user = auth()->user();

                $bookmark = Bookmark::query()->create([
                    'name' => $data['name'],
                    'type' => $arguments['type'] ?? 'folder',
                    'is_private' => false,
                    'user_type' => $user ? $user::class : null,
                    'user_id' => $user?->getAuthIdentifier(),
                ]);

                Notification::make()
                    ->title(trans('filament-bookmarks-menu::messages.livewire.create.notification.title'))
                    ->body(trans('filament-bookmarks-menu::messages.livewire.create.notification.body'))
                    ->success()
                    ->send();

                return redirect()->to(Bookmarks::getUrl(['id' => $bookmark->id]));
            });
    }

    /**
     * @return array<int, array{key: string, label: string}>
     */
    public function getGroups(): array
    {
        $panelId = filament()->getCurrentOrDefaultPanel()?->getId();

        $groups = collect(FilamentBookmarksMenu::load())
            ->filter(fn (BookmarkType $type): bool => ! $type->panel || $type->panel === $panelId)
            ->map(fn (BookmarkType $type): array => [
                'key' => $type->key,
                'label' => str($type->label)->contains('.') ? trans($type->label) : $type->label,
            ])
            ->values()
            ->all();

        if (count($groups)) {
            return $groups;
        }

        return [[
            'key' => 'folder',
            'label' => trans('filament-bookmarks-menu::messages.components.folders'),
        ]];
    }

    /**
     * @return Collection<int, Bookmark>
     */
    public function getBookmarks(string $type): Collection
    {
        return Bookmark::query()
            ->where('type', $type)
            ->visibleTo(auth()->user())
            ->withCount('links')
            ->orderBy('name')
            ->get();
    }

    public function render(): View
    {
        return view('filament-bookmarks-menu::livewire.bookmark-sidebar');
    }
}
