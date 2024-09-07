<?php

namespace TomatoPHP\FilamentBookmarksMenu\Filament\Pages;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use TomatoPHP\FilamentBookmarksMenu\Models\Bookmark;
use TomatoPHP\FilamentBookmarksMenu\Models\BookmarkLink;
use TomatoPHP\FilamentIcons\Components\IconColumn;
use TomatoPHP\FilamentIcons\Components\IconPicker;

class Bookmarks extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $view = 'filament-bookmarks-menu::pages.bookmarks';

    public Bookmark $bookmark;

    public function table(Table $table): Table
    {
        return $table
            ->query(BookmarkLink::query()->whereHas('bookmarks', function ($query){
                $query->where('bookmark_id', $this->bookmark->id);
            }))
            ->columns([
                TextColumn::make('name')
                    ->label(trans('filament-bookmarks-menu::messages.page.table.name'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('url')
                    ->label(trans('filament-bookmarks-menu::messages.page.table.url'))
                    ->label('Bookmark')
                    ->view('filament-bookmarks-menu::columns.name'),
            ])
            ->actions([
                \Filament\Tables\Actions\Action::make('remove')
                    ->tooltip(trans('filament-bookmarks-menu::messages.page.table.actions.remove.lable'))
                    ->requiresConfirmation()
                    ->color('danger')
                    ->label(trans('filament-bookmarks-menu::messages.page.table.actions.remove.lable'))
                    ->modalHeading(trans('filament-bookmarks-menu::messages.page.table.actions.remove.modal'))
                    ->icon('heroicon-s-bookmark-slash')
                    ->hiddenLabel()
                    ->action(function($record){
                        $record->bookmarks()->detach($this->bookmark->id);

                        if($record->bookmarks()->count() == 0){
                            $record->delete();
                        }

                        Notification::make()
                            ->title(trans('filament-bookmarks-menu::messages.page.table.actions.remove.notification.title'))
                            ->body(trans('filament-bookmarks-menu::messages.page.table.actions.remove.notification.body'))
                            ->success()
                            ->send();
                    })
            ])
            ->bulkActions([
                BulkAction::make('remove_bluk')
                    ->requiresConfirmation()
                    ->color('danger')
                    ->label(trans('filament-bookmarks-menu::messages.page.table.actions.bulk.label'))
                    ->modalHeading(trans('filament-bookmarks-menu::messages.page.table.actions.bulk.modal'))
                    ->deselectRecordsAfterCompletion()
                    ->icon('heroicon-s-bookmark-slash')
                    ->action(function (array $data, $records) {
                        $records->each(function ($record){
                            $record->bookmarks()->detach($this->bookmark->id);

                            if($record->bookmarks()->count() == 0){
                                $record->delete();
                            }
                        });

                        Notification::make()
                            ->title(trans('filament-bookmarks-menu::messages.page.table.actions.bulk.notification.title'))
                            ->body(trans('filament-bookmarks-menu::messages.page.table.actions.bulk.notification.body'))
                            ->success()
                            ->send();
                    })
            ]);
    }

    public function mount()
    {
        if(!request()->has('id')){
            abort(404);
        }
        else {
            $this->bookmark = Bookmark::find(request()->get('id'));
            if(!$this->bookmark){
                abort(404);
            }
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
                ->action(function (){
                    $this->bookmark->links()->delete();
                    $this->bookmark->delete();

                    Notification::make()
                        ->title(trans('filament-bookmarks-menu::messages.page.actions.delete.notification.title'))
                        ->body(trans('filament-bookmarks-menu::messages.page.actions.delete.notification.body'))
                        ->success()
                        ->send();

                    return redirect()->to(filament()->getCurrentPanel()->getUrl());
                }),
            Action::make('edit')
                ->icon('heroicon-s-pencil')
                ->color('warning')
                ->label(trans('filament-bookmarks-menu::messages.page.actions.edit.label'))
                ->modalHeading(trans('filament-bookmarks-menu::messages.page.actions.edit.modal'))
                ->fillForm($this->bookmark->toArray())
                ->form([
                    Grid::make([
                        'md' => 2,
                        'sm' => 1
                    ])
                    ->schema([
                        TextInput::make('name')
                            ->label(trans('filament-bookmarks-menu::messages.page.actions.edit.form.name'))
                            ->unique('bookmarks', 'name', ignorable: $this->bookmark)
                            ->required()
                            ->columnSpanFull(),
                        IconPicker::make('icon')
                            ->label(trans('filament-bookmarks-menu::messages.page.actions.edit.form.icon')),
                        ColorPicker::make('color')
                            ->label(trans('filament-bookmarks-menu::messages.page.actions.edit.form.color')),
                        Toggle::make('is_private')
                            ->label(trans('filament-bookmarks-menu::messages.page.actions.edit.form.is_private'))
                            ->columnSpanFull()
                            ->live(),
                    ])
                ])
                ->action(function (array $data){
                    if($data['is_private']){
                        $data['user_type'] = get_class(auth()->user());
                        $data['user_id'] = auth()->id();
                    }

                    $this->bookmark->update($data);

                    Notification::make()
                        ->title(trans('filament-bookmarks-menu::messages.page.actions.edit.notification.title'))
                        ->body(trans('filament-bookmarks-menu::messages.page.actions.edit.notification.body'))
                        ->success()
                        ->send();

                    return redirect()->to(static::getUrl() . '?id=' . $this->bookmark->id);
                })
        ];
    }

    public function getTitle(): string|Htmlable
    {
        if($this->bookmark){
            return $this->bookmark->name;
        }
        else {
            return trans('filament-bookmarks-menu::messages.page.title');
        }
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
