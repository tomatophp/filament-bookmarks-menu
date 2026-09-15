<?php

namespace TomatoPHP\FilamentBookmarksMenu\Filament\Pages;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Locked;
use TomatoPHP\FilamentBookmarksMenu\Models\Bookmark;
use TomatoPHP\FilamentBookmarksMenu\Models\BookmarkLink;
use TomatoPHP\FilamentIcons\Components\IconPicker;

class Bookmarks extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'filament-bookmarks-menu::pages.bookmarks';

    protected static bool $shouldRegisterNavigation = false;

    #[Locked]
    public Bookmark $bookmark;

    public function mount(): void
    {
        $bookmark = Bookmark::query()
            ->visibleTo(auth()->user())
            ->find(request()->query('id'));

        abort_if(! $bookmark, 404);

        $this->bookmark = $bookmark;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn () => BookmarkLink::query()->whereHas('bookmarks', function ($query): void {
                $query->whereKey($this->bookmark->getKey());
            }))
            ->columns([
                TextColumn::make('name')
                    ->label(trans('filament-bookmarks-menu::messages.page.table.name'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('url')
                    ->label(trans('filament-bookmarks-menu::messages.page.table.url'))
                    ->view('filament-bookmarks-menu::columns.name'),
            ])
            ->recordActions([
                Action::make('remove')
                    ->tooltip(trans('filament-bookmarks-menu::messages.page.table.actions.remove.lable'))
                    ->requiresConfirmation()
                    ->color('danger')
                    ->label(trans('filament-bookmarks-menu::messages.page.table.actions.remove.lable'))
                    ->modalHeading(trans('filament-bookmarks-menu::messages.page.table.actions.remove.modal'))
                    ->icon('heroicon-s-bookmark-slash')
                    ->hiddenLabel()
                    ->action(function (BookmarkLink $record): void {
                        $this->detachLink($record);

                        Notification::make()
                            ->title(trans('filament-bookmarks-menu::messages.page.table.actions.remove.notification.title'))
                            ->body(trans('filament-bookmarks-menu::messages.page.table.actions.remove.notification.body'))
                            ->success()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkAction::make('remove_bluk')
                    ->requiresConfirmation()
                    ->color('danger')
                    ->label(trans('filament-bookmarks-menu::messages.page.table.actions.bulk.label'))
                    ->modalHeading(trans('filament-bookmarks-menu::messages.page.table.actions.bulk.modal'))
                    ->deselectRecordsAfterCompletion()
                    ->icon('heroicon-s-bookmark-slash')
                    ->action(function (Collection $records): void {
                        $records->each(fn (BookmarkLink $record) => $this->detachLink($record));

                        Notification::make()
                            ->title(trans('filament-bookmarks-menu::messages.page.table.actions.bulk.notification.title'))
                            ->body(trans('filament-bookmarks-menu::messages.page.table.actions.bulk.notification.body'))
                            ->success()
                            ->send();
                    }),
            ]);
    }

    protected function detachLink(BookmarkLink $link): void
    {
        $link->bookmarks()->detach($this->bookmark->getKey());

        if ($link->bookmarks()->count() === 0) {
            $link->delete();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('delete')
                ->icon('heroicon-s-trash')
                ->label(trans('filament-bookmarks-menu::messages.page.actions.delete.label'))
                ->modalHeading(trans('filament-bookmarks-menu::messages.page.actions.delete.modal'))
                ->color('danger')
                ->requiresConfirmation()
                ->action(function () {
                    $this->bookmark->links()->each(fn (BookmarkLink $link) => $this->detachLink($link));
                    $this->bookmark->delete();

                    Notification::make()
                        ->title(trans('filament-bookmarks-menu::messages.page.actions.delete.notification.title'))
                        ->body(trans('filament-bookmarks-menu::messages.page.actions.delete.notification.body'))
                        ->success()
                        ->send();

                    return redirect()->to(filament()->getCurrentOrDefaultPanel()->getUrl());
                }),
            Action::make('edit')
                ->icon('heroicon-s-pencil')
                ->color('warning')
                ->label(trans('filament-bookmarks-menu::messages.page.actions.edit.label'))
                ->modalHeading(trans('filament-bookmarks-menu::messages.page.actions.edit.modal'))
                ->fillForm(fn (): array => $this->bookmark->only(['name', 'icon', 'color', 'is_private']))
                ->schema([
                    Grid::make([
                        'md' => 2,
                        'sm' => 1,
                    ])
                        ->schema([
                            TextInput::make('name')
                                ->label(trans('filament-bookmarks-menu::messages.page.actions.edit.form.name'))
                                ->unique('bookmarks', 'name', ignorable: $this->bookmark)
                                ->maxLength(255)
                                ->required()
                                ->columnSpanFull(),
                            IconPicker::make('icon')
                                ->label(trans('filament-bookmarks-menu::messages.page.actions.edit.form.icon')),
                            ColorPicker::make('color')
                                ->label(trans('filament-bookmarks-menu::messages.page.actions.edit.form.color')),
                            Toggle::make('is_private')
                                ->label(trans('filament-bookmarks-menu::messages.page.actions.edit.form.is_private'))
                                ->columnSpanFull(),
                        ]),
                ])
                ->action(function (array $data) {
                    $user = auth()->user();

                    if (($data['is_private'] ?? false) && $user) {
                        $data['user_type'] = $user::class;
                        $data['user_id'] = $user->getAuthIdentifier();
                    }

                    $this->bookmark->update($data);

                    Notification::make()
                        ->title(trans('filament-bookmarks-menu::messages.page.actions.edit.notification.title'))
                        ->body(trans('filament-bookmarks-menu::messages.page.actions.edit.notification.body'))
                        ->success()
                        ->send();

                    return redirect()->to(static::getUrl(['id' => $this->bookmark->getKey()]));
                }),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return isset($this->bookmark)
            ? $this->bookmark->name
            : trans('filament-bookmarks-menu::messages.page.title');
    }
}
