<?php

namespace TomatoPHP\FilamentBookmarksMenu\Livewire;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Livewire\Component;
use TomatoPHP\FilamentBookmarksMenu\Filament\Pages\Bookmarks;
use TomatoPHP\FilamentBookmarksMenu\Models\Bookmark;

class BookmarkSidebar extends Component implements HasActions, HasForms
{
    use InteractsWithForms;
    use InteractsWithActions;

    public function getCreateAction(string $type = 'folder'): Action
    {
        return Action::make('getCreateAction')
            ->iconButton()
            ->icon('heroicon-s-plus-circle')
            ->tooltip(trans('filament-bookmarks-menu::messages.livewire.create.label'))
            ->color('gray')
            ->label(trans('filament-bookmarks-menu::messages.livewire.create.label'))
            ->modalHeading(trans('filament-bookmarks-menu::messages.livewire.create.modal'))
            ->form([
                TextInput::make('name')
                    ->label(trans('filament-bookmarks-menu::messages.livewire.create.form.name'))
                    ->required()
            ])
            ->action(function (array $data, array $arguments){
                $data['type'] = $arguments['type'];
                $bookmark = Bookmark::query()->create($data);


                Notification::make()
                    ->title(trans('filament-bookmarks-menu::messages.livewire.create.notification.title'))
                    ->body(trans('filament-bookmarks-menu::messages.livewire.create.notification.body'))
                    ->success()
                    ->send();

                return redirect()->to(Bookmarks::getUrl() . '?id=' . $bookmark->id);
            });
    }

    public function render()
    {
        return view('filament-bookmarks-menu::livewire.bookmark-sidebar');
    }
}
